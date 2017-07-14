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
use LaravelEnso\Risco\app\Classes\RiscoRequest;
use LaravelEnso\Risco\app\Classes\TokenRequestHub;
use LaravelEnso\Risco\app\Enums\DataTypesEnum;
use LaravelEnso\Risco\app\Enums\SubscribedAppTypesEnum;
use LaravelEnso\Risco\app\Http\Requests\ValidateAppSubscriptionRequest;
use LaravelEnso\Risco\app\Models\SubscribedApp;
use Meng\AsyncSoap\Guzzle\Factory;
use Phpro\SoapClient\ClientBuilder;
use Phpro\SoapClient\ClientFactory;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;
use Phpro\SoapClient\Soap\Handler\GuzzleHandle;
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
            'IID' => 0,
            'STS' => 0,
        ];

        $FinServiceReq = [
            'CUI'      => '22197648', //15565607
            'DataType' => $DataType,
        ];

        $FinReq = [
            'HeaderReq'     => $HeaderReq,
            'FinServiceReq' => $FinServiceReq,
        ];

        $WSDL = 'http://dev.risco.ro/RiscoWs/RapoarteRisco.php?wsdl';



        //phpro
        $request = new MultiArgumentRequest(['FinReq' => $FinReq]);
        //$request = new RiscoRequest($FinReq);

        $clientFactory = new ClientFactory(RiscoClient::class);
        $soapOptions = [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace'         => 1,
            'exceptions'    => 1,
        ];

        $clientBuilder = new ClientBuilder($clientFactory, $WSDL, $soapOptions);
        //$clientBuilder->withLogger(new Logger());
        //$clientBuilder->withEventDispatcher(new EventDispatcher());
        $clientBuilder->withClassMaps($this->getClassMaps());
        //$clientBuilder->addTypeConverter(new DateTimeTypeConverter());
        $guzzleClient = new Client();
        $clientBuilder->withHandler(GuzzleHandle::createForClient($guzzleClient));
        $client = $clientBuilder->build();

        $response = $client->getFinancialInfo($request);
        $result = $response->getResult();

        $this->processFin_ResRawData($result);


        Log::info($result);
        return $result;
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

    private function getClassMaps()
    {
        return new ClassMapCollection([
            new ClassMap('RiscoReq', \LaravelEnso\Risco\app\Classes\Generated\RiscoReq::class),
            new ClassMap('FinReq', \LaravelEnso\Risco\app\Classes\Generated\FinReq::class),
            new ClassMap('RiscoRes', \LaravelEnso\Risco\app\Classes\Generated\RiscoRes::class),
            new ClassMap('HeaderReq', \LaravelEnso\Risco\app\Classes\Generated\HeaderReq::class),
            new ClassMap('ServiceReq', \LaravelEnso\Risco\app\Classes\Generated\ServiceReq::class),
            new ClassMap('FinServiceReq', \LaravelEnso\Risco\app\Classes\Generated\FinServiceReq::class),
            new ClassMap('HeaderRes', \LaravelEnso\Risco\app\Classes\Generated\HeaderRes::class),
            new ClassMap('Reports', \LaravelEnso\Risco\app\Classes\Generated\Reports::class),
            new ClassMap('DataType', \LaravelEnso\Risco\app\Classes\Generated\DataType::class),
            new ClassMap('Rapoarte_Res', \LaravelEnso\Risco\app\Classes\Generated\Rapoarte_Res::class),
            new ClassMap('Errors', \LaravelEnso\Risco\app\Classes\Generated\Errors::class),
            new ClassMap('JUST_Res', \LaravelEnso\Risco\app\Classes\Generated\JUST_Res::class),
            new ClassMap('RAT_Res', \LaravelEnso\Risco\app\Classes\Generated\RAT_Res::class),
            new ClassMap('RES_Res', \LaravelEnso\Risco\app\Classes\Generated\RES_Res::class),
            new ClassMap('LCO_Res', \LaravelEnso\Risco\app\Classes\Generated\LCO_Res::class),
            new ClassMap('ACT_Res', \LaravelEnso\Risco\app\Classes\Generated\ACT_Res::class),
            new ClassMap('ISACT_Res', \LaravelEnso\Risco\app\Classes\Generated\ISACT_Res::class),
            new ClassMap('ONRC_Res', \LaravelEnso\Risco\app\Classes\Generated\ONRC_Res::class),
            new ClassMap('BI_Res', \LaravelEnso\Risco\app\Classes\Generated\BI_Res::class),
            new ClassMap('CIP_Res', \LaravelEnso\Risco\app\Classes\Generated\CIP_Res::class),
            new ClassMap('PIM_Res', \LaravelEnso\Risco\app\Classes\Generated\PIM_Res::class),
            new ClassMap('FinancialInfo', \LaravelEnso\Risco\app\Classes\Generated\FinancialInfo::class),
            new ClassMap('Financial_Res', \LaravelEnso\Risco\app\Classes\Generated\Financial_Res::class),
            new ClassMap('FIN_Res', \LaravelEnso\Risco\app\Classes\Generated\FIN_Res::class),
            new ClassMap('IID_Res', \LaravelEnso\Risco\app\Classes\Generated\IID_Res::class),
            new ClassMap('STS_Res', \LaravelEnso\Risco\app\Classes\Generated\STS_Res::class),
        ]);


    }

    private function processFin_ResRawData(&$result)
    {

        if(!$result->getFinancial_Res()->getFIN_Res()) {
            return;
        }

        $xmlString = $result->getFinancial_Res()->getFIN_Res()->getRawData();
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        $array = json_decode($json,TRUE);

        $result->getFinancial_Res()->getFIN_Res()->setRawData($array);
    }
}
