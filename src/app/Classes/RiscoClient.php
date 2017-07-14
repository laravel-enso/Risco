<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 15.06.2017
 * Time: 12:04.
 */

namespace LaravelEnso\Risco\app\Classes;

use Phpro\SoapClient\Client;
use Phpro\SoapClient\Type\RequestInterface;

class RiscoClient extends Client
{
    public function getFinancialInfo(RequestInterface $request)
    {
        \Log::debug('hit getFinancialInfo');

        return $this->call('getFinancialInfo', $request);
    }
}
