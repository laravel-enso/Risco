<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class FinancialInfo implements JsonSerializable
{
    /**
     * @var Financial_Res
     */
    private $Financial_Res = null;

    /**
     * @var Errors
     */
    private $Errors = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'Financial_Res' => $this->Financial_Res,
            'Errors' => $this->Errors,
        ];
    }

    /**
     * @return Financial_Res
     */
    public function getFinancial_Res()
    {
        return $this->Financial_Res;
    }

    /**
     * @return Errors
     */
    public function getErrors()
    {
        return $this->Errors;
    }
}
