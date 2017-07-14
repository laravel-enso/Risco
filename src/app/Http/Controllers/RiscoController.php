<?php

namespace LaravelEnso\Risco\app\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LaravelEnso\Core\app\Exceptions\EnsoException;
use LaravelEnso\Risco\app\Classes\ApiRequestHub;
use LaravelEnso\Risco\app\Classes\PreferencesStructureBuilder;
use LaravelEnso\Risco\app\Classes\ResponseDataWrapper;
use LaravelEnso\Risco\app\Classes\RiscoClient;
use LaravelEnso\Risco\app\Classes\TokenRequestHub;
use LaravelEnso\Risco\app\Enums\DataTypesEnum;
use LaravelEnso\Risco\app\Enums\SubscribedAppTypesEnum;
use LaravelEnso\Risco\app\Http\Requests\ValidateAppSubscriptionRequest;
use LaravelEnso\Risco\app\Models\SubscribedApp;
use Meng\AsyncSoap\Guzzle\Factory;
use Phpro\SoapClient\ClientBuilder;
use Phpro\SoapClient\ClientFactory;
use Phpro\SoapClient\Soap\TypeConverter\DateTimeTypeConverter;
use Phpro\SoapClient\Type\MultiArgumentRequest;
use SoapClient;

class RiscoController extends Controller
{
    public function identification()
    {
        $user = 'office@earthlink.ro';
        $pass = 'earth104';

        //$client = new Client();

        $HeaderReq = [
            'channel'      => 'RISCO_RAP',
            'extref'       => '111', // identificator furnizat de client, va fi intors in raspuns
            'intref'       => '', // identificator intern furnizat de risco
            'daterequest'  => date('Y-m-d H:i:s'), // data cererii
            'dateresponse' => '', // data raspunsului
            'psign'        => '', // calculat cu formula din documentatie
            'user'         => $user, // utilizator cont risco
            'password'     => $pass, // parola cont risco
        ];

        $key = '46ad1fc5cxzd4fe98646ud9fcr83';
        $HeaderReq['psign'] = md5($HeaderReq['extref'].$HeaderReq['user'].$key.$HeaderReq['daterequest'].$HeaderReq['channel']);
        Log::debug('PSIGN CALCULAT DE LA CLIENT: '.$HeaderReq['psign']);

        $DataType = [
            'FIN' => 1,
            'IID' => 1,
            'STS' => 1,
        ];

        $FinServiceReq = [
            'CUI'      => '22197648',
            'DataType' => $DataType,
        ];

        $FinReq = [
            'HeaderReq'     => $HeaderReq,
            'FinServiceReq' => $FinServiceReq,
        ];

        $WSDL = 'http://dev.risco.ro/RiscoWs/RapoarteRisco.php?wsdl';

        //meng-tian
        /* $factory = new Factory();
         $client = $factory->create(new Client(), $WSDL);
         $result = $client->call('getFinancialInfo', $FinReq);
         return $result;*/

        //basic
        try {
            $objClient = new SoapClient($WSDL, [
                'trace'         => 1,
                'exceptions'    => 1,
            ]);
            //$objClient->__setSoapHeaders($HeaderReq);
            $response = $objClient->__soapCall('getFinancialInfo', ['FinReq' => $FinReq]);

            return (array) $response;
        } catch (\Exception $e) {
            \Log::debug('Request: ');
            \Log::debug($objClient->__getLastRequest());
            \Log::debug('Response: ');
            \Log::debug($objClient->__getLastResponse());
            \Log::info($e->getMessage());
            \Log::debug($e->getTraceAsString());

            return $e->getMessage();
        }

        //phpro
        /*$request = new MultiArgumentRequest($FinServiceReq);

        $clientFactory = new ClientFactory(RiscoClient::class);
        $soapOptions = [
            'cache_wsdl' => WSDL_CACHE_NONE
        ];

        $clientBuilder = new ClientBuilder($clientFactory, $WSDL, $soapOptions);
        //$clientBuilder->withLogger(new Logger());
        //$clientBuilder->withEventDispatcher(new EventDispatcher());
        //$clientBuilder->addClassMap(new ClassMap('WsdlType', PhpType::class));
        //$clientBuilder->addTypeConverter(new DateTimeTypeConverter());
        $client = $clientBuilder->build();

        $response = $client->getFinancialInfo($request);*/

        return $response;
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
