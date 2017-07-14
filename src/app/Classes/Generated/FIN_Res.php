<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class FIN_Res implements JsonSerializable
{

    /**
     * @var string
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
            'Error' => $this->Error,
        ];
    }

    /**
     * @return string
     */
    public function getRawData()
    {
        return $this->RawData;
    }

    public function setRawData($rawData)
    {
        $this->RawData = $rawData;
    }

    /**
     * @return anyType
     */
    public function getError()
    {
        return $this->Error;
    }


}

