<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class FinReq implements JsonSerializable
{
    /**
     * @var HeaderReq
     */
    private $HeaderReq = null;

    /**
     * @var FinServiceReq
     */
    private $FinServiceReq = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'HeaderReq'     => $this->HeaderReq,
            'FinServiceReq' => $this->FinServiceReq,
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
     * @return FinServiceReq
     */
    public function getFinServiceReq()
    {
        return $this->FinServiceReq;
    }
}
