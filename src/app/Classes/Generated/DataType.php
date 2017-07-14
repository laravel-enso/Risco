<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class DataType implements JsonSerializable
{
    /**
     * @var int
     */
    private $FIN = null;

    /**
     * @var int
     */
    private $IID = null;

    /**
     * @var int
     */
    private $STS = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'FIN' => $this->FIN,
            'IID' => $this->IID,
            'STS' => $this->STS,
        ];
    }

    /**
     * @return int
     */
    public function getFIN()
    {
        return $this->FIN;
    }

    /**
     * @return int
     */
    public function getIID()
    {
        return $this->IID;
    }

    /**
     * @return int
     */
    public function getSTS()
    {
        return $this->STS;
    }
}
