<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 15.06.2017
 * Time: 12:04.
 */

namespace LaravelEnso\Risco\app\Classes;

use Illuminate\Support\Facades\Log;
use LaravelEnso\Core\app\Exceptions\EnsoException;
use Phpro\SoapClient\Client;
use Phpro\SoapClient\Type\RequestInterface;

class RiscoClient extends Client
{

    public function getFinancialInfo(RequestInterface $request)
    {

        //try catch is necessary because of crappy response handling from Phpro / Guzzle
        try {

            return $this->call('getFinancialInfo', $request);

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            Log::debug(__CLASS__ . ' @ ' . __FUNCTION__ . ' @ ' . $e->getLine());
            Log::error($e->getTraceAsString());

            //throw our own exception for friendlier reporting
            throw new EnsoException(__('There was an error requesting RisCo info for the given IFSC'));
        }
    }
}