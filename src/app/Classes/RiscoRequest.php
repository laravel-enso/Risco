<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 13.06.2017
 * Time: 17:39.
 */

namespace LaravelEnso\Risco\app\Classes;

use Phpro\SoapClient\Type\RequestInterface;

class RiscoRequest implements RequestInterface
{
    public $FinReq = [];

    public function __construct($params)
    {
        $this->FinReq = $params;
    }
}
