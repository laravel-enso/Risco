<?php

namespace LaravelEnso\Risco\app\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelEnso\Risco\app\Classes\ApiRequestHub;
use LaravelEnso\Risco\app\Classes\Formatters\FINResponse;
use LaravelEnso\Risco\app\Classes\Formatters\IIDResponse;
use LaravelEnso\Risco\app\Classes\Formatters\STSResponse;
use LaravelEnso\Risco\app\Classes\ResponseDataWrapper;
use LaravelEnso\Risco\app\Classes\RiscoClient;
use LaravelEnso\Risco\app\Enums\DataTypesEnum;
use Phpro\SoapClient\ClientBuilder;
use Phpro\SoapClient\ClientFactory;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;
use Phpro\SoapClient\Soap\Handler\GuzzleHandle;
use Phpro\SoapClient\Type\MultiArgumentRequest;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;


class RiscoController extends Controller
{
    public function query(Request $request)
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
            'FIN' => $request->get('fin'),
            'IID' => $request->get('iid'),
            'STS' => $request->get('sts'),
        ];

        $FinServiceReq = [
            'CUI'      => $request->get('cui'), //15565607
            'DataType' => $DataType,
        ];

        $FinReq = [
            'HeaderReq'     => $HeaderReq,
            'FinServiceReq' => $FinServiceReq,
        ];

        $WSDL = 'http://dev.risco.ro/RiscoWs/RapoarteRisco.php?wsdl';


        // create a log channel
        //$log = new Logger('name');
        //$log->pushHandler(new StreamHandler('/home/mihai/work/_proj/enso/storage/logs/your.log', Logger::WARNING));
        //$log->warning('Foo');

        //phpro
        $request = new MultiArgumentRequest(['FinReq' => $FinReq]);

        $clientFactory = new ClientFactory(RiscoClient::class);
        $soapOptions = [
            'cache_wsdl'    => WSDL_CACHE_NONE,
            'trace'         => 1,
            'exceptions'    => 1,
        ];

        $clientBuilder = new ClientBuilder($clientFactory, $WSDL, $soapOptions);
        //$clientBuilder->withLogger($log);
        $clientBuilder->withEventDispatcher(new EventDispatcher());
        $clientBuilder->withClassMaps($this->getClassMaps());
        //$clientBuilder->addTypeConverter(new DateTimeTypeConverter());

        $guzzleClient = new Client();
        $clientBuilder->withHandler(GuzzleHandle::createForClient($guzzleClient));

        $client = $clientBuilder->build();

        $response = $client->getFinancialInfo($request);
        $result = $response->getResult();

        $this->processFin_ResRawData($result);


        $processedFinResult = FINResponse::format($result->getFinancial_Res()->getFIN_Res());
        $processedIidResult = IIDResponse::format($result->getFinancial_Res()->getIID_Res());
        $processedStsResult = STSResponse::format($result->getFinancial_Res()->getSTS_Res());


        return [
            'FIN_Res' => $processedFinResult,
            'IID_Res' => $processedIidResult,
            'STS_Res' => $processedStsResult,
        ];
    }



    public function index()
    {
        return view('laravel-enso/risco::risco.index',
            compact(''));
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
        if (!$result->getFinancial_Res()->getFIN_Res()) {
            return;
        }

        $xmlString = $result->getFinancial_Res()->getFIN_Res()->getRawData();
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        $array = json_decode($json, true);

        $result->getFinancial_Res()->getFIN_Res()->setRawData($array);
    }
}
