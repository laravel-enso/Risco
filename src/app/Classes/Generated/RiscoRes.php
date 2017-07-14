<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class RiscoRes implements JsonSerializable
{

    /**
     * @var HeaderRes
     */
    private $HeaderRes = null;

    /**
     * @var ServiceReq
     */
    private $ServiceReq = null;

    /**
     * @var Rapoarte_Res
     */
    private $Rapoarte_Res = null;

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
            'HeaderRes' => $this->HeaderRes,
            'ServiceReq' => $this->ServiceReq,
            'Rapoarte_Res' => $this->Rapoarte_Res,
            'Errors' => $this->Errors,
        ];
    }

    /**
     * @return HeaderRes
     */
    public function getHeaderRes()
    {
        return $this->HeaderRes;
    }

    /**
     * @return ServiceReq
     */
    public function getServiceReq()
    {
        return $this->ServiceReq;
    }

    /**
     * @return Rapoarte_Res
     */
    public function getRapoarte_Res()
    {
        return $this->Rapoarte_Res;
    }

    /**
     * @return Errors
     */
    public function getErrors()
    {
        return $this->Errors;
    }


}

