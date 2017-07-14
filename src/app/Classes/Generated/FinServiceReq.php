<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class FinServiceReq implements JsonSerializable
{
    /**
     * @var string
     */
    private $CUI = null;

    /**
     * @var DataType
     */
    private $DataType = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'CUI'      => $this->CUI,
            'DataType' => $this->DataType,
        ];
    }

    /**
     * @return string
     */
    public function getCUI()
    {
        return $this->CUI;
    }

    /**
     * @return DataType
     */
    public function getDataType()
    {
        return $this->DataType;
    }
}
