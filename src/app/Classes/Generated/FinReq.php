<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

class FinReq
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
