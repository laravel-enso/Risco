<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class IID_Res implements JsonSerializable
{
    /**
     * @var anyType
     */
    private $RawData = null;

    /**
     * @var anyType
     */
    private $Error = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'RawData' => $this->RawData,
            'Error'   => $this->Error,
        ];
    }

    /**
     * @return anyType
     */
    public function getRawData()
    {
        return $this->RawData;
    }

    /**
     * @return anyType
     */
    public function getError()
    {
        return $this->Error;
    }
}
