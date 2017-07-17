<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class RiscoReq implements JsonSerializable
{
    /**
     * @var HeaderReq
     */
    private $HeaderReq = null;

    /**
     * @var ServiceReq
     */
    private $ServiceReq = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'HeaderReq'  => $this->HeaderReq,
            'ServiceReq' => $this->ServiceReq,
        ];
    }

    /**
     * @return HeaderReq
     */
    public function getHeaderReq()
    {
        return $this->HeaderReq;
    }

    /**
     * @return ServiceReq
     */
    public function getServiceReq()
    {
        return $this->ServiceReq;
    }
}
