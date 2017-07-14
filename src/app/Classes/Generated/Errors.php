<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class Errors implements JsonSerializable
{
    /**
     * @var string
     */
    private $ErrorCode = null;

    /**
     * @var string
     */
    private $ErrorDetails = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'ErrorCode' => $this->ErrorCode,
            'ErrorDetails' => $this->ErrorDetails,
        ];
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->ErrorCode;
    }

    /**
     * @return string
     */
    public function getErrorDetails()
    {
        return $this->ErrorDetails;
    }
}
