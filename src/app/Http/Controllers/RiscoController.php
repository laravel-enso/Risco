<?php

namespace LaravelEnso\Risco\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LaravelEnso\Risco\app\Classes\ApiRequestHub;
use LaravelEnso\Risco\app\Classes\PreferencesStructureBuilder;
use LaravelEnso\Risco\app\Classes\ResponseDataWrapper;
use LaravelEnso\Risco\app\Classes\TokenRequestHub;
use LaravelEnso\Risco\app\Enums\DataTypesEnum;
use LaravelEnso\Risco\app\Enums\SubscribedAppTypesEnum;
use LaravelEnso\Risco\app\Http\Requests\ValidateAppSubscriptionRequest;
use LaravelEnso\Risco\app\Models\SubscribedApp;
use LaravelEnso\Core\app\Exceptions\EnsoException;

class RiscoController extends Controller
{
    public function updatePreferences(Request $request, SubscribedApp $subscribedApp)
    {
        $subscribedApp->preferences = json_encode($request->get('preferences'));
        $subscribedApp->save();
    }

    public function setMaintenanceMode(Request $request, SubscribedApp $subscribedApp)
    {
        $exitCode = ApiRequestHub::setMaintenanceMode($request, $subscribedApp);

        return [
            'message' => __('Application is now in maintenance mode'),
        ];
    }

    public function destroy(SubscribedApp $subscribedApp)
    {
        DB::transaction(function () use ($subscribedApp) {
            $subscribedApp->delete();
            $tokenResponseData = TokenRequestHub::deleteToken(
                $subscribedApp->type,
                $subscribedApp->url,
                $subscribedApp->token
            );

            $responseStatusCode = $tokenResponseData->getStatusCode();
            if ($responseStatusCode !== 200) {
                throw new EnsoException(__('Could not delete token'));
            }

            return 'Deleted';
        });
    }

    public function index()
    {
        $activeApps = json_encode(SubscribedApp::orderBy('name')->get());
        $subscribedAppTypes = (new SubscribedAppTypesEnum())->getJsonKVData();
        $dataTypes = (new DataTypesEnum())->getJsonKVData();

        return view('laravel-enso/Risco::Risco.index',
            compact('activeApps', 'subscribedAppTypes', 'dataTypes'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            throw new EnsoException('The form has errors', 'error', $validator->errors()->toArray(), 422);
        }

        $tokenResponseData = $this->getClientToken($request);

        try {
            $newSubscribedApp = null;

            DB::transaction(function () use ($request, $tokenResponseData, &$newSubscribedApp) {
                $newSubscribedApp = new SubscribedApp($request->all());
                $newSubscribedApp->token = $tokenResponseData->access_token;
                $newSubscribedApp->preferences = PreferencesStructureBuilder::build($request->get('type'));
                $newSubscribedApp->save();
            });

            return $newSubscribedApp;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            TokenRequestHub::deleteToken(
                $request->get('type'),
                $request->get('url'),
                $tokenResponseData->access_token);

            return response('Server Error', 500);
        }
    }

    public function get(Request $request, SubscribedApp $subscribedApp)
    {
        $result = new ResponseDataWrapper($subscribedApp->id, $subscribedApp->name, $subscribedApp->type);

        try {
            $response = ApiRequestHub::getAll($request, $subscribedApp);
            $originalData = json_decode($response->getBody(), true);
            $result->data = $this->translateData($originalData);
        } catch (\Exception $e) {
            $result->addError($e->getMessage());
        }

        return $result;
    }

    public function clearLaravelLog(Request $request, SubscribedApp $subscribedApp)
    {
        $response = ApiRequestHub::clearLaravelLog($request, $subscribedApp);

        return [
            'message' => __('Application Log deleted!'),
        ];
    }

    private function translateData($originalData)
    {
        $types = (new DataTypesEnum())->getData();
        $translatedData = json_decode(json_encode($originalData), true);

        for ($i = 0; $i < count($translatedData); $i++) {
            $key = $translatedData[$i]['key'];
            $translatedData[$i]['key'] = $types[$key];
        }

        return $translatedData;
    }

    private function validateRequest(Request $request)
    {
        $rules = (new ValidateAppSubscriptionRequest())->rules();
        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    /**
     * @param Request $request
     *
     * @throws EnsoException
     *
     * @return \LaravelEnso\Helpers\Classes\Object|object
     */
    private function getClientToken(Request $request)
    {
        try {
            $tokenResponseData = TokenRequestHub::requestNewToken($request);
        } catch (\Exception $e) {
            throw new EnsoException(__('Unable to communicate with server. Check URL!'));
        }

        if (!$tokenResponseData) {
            throw new EnsoException(__('Unable to get valid token. Check oauth data!'));
        }

        return $tokenResponseData;
    }
}
