<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 13.06.2017
 * Time: 17:39.
 */

namespace LaravelEnso\Risco\app\Classes;

use Illuminate\Http\Request;
use LaravelEnso\Core\app\Exceptions\EnsoException;
use LaravelEnso\Risco\app\Models\SubscribedApp;
use Phpro\SoapClient\Type\RequestInterface;

class RiscoRequest implements RequestInterface
{
    public $FinReq = [];

    public function __construct($params)
    {

        $this->FinReq = $params;
    }
}
