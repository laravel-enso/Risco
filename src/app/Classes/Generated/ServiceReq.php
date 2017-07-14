<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class ServiceReq implements JsonSerializable
{
    /**
     * @var string
     */
    private $CUI = null;

    /**
     * @var Reports
     */
    private $Reports = null;

    /**
     * @var string
     */
    private $ContentType = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'CUI'         => $this->CUI,
            'Reports'     => $this->Reports,
            'ContentType' => $this->ContentType,
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
     * @return Reports
     */
    public function getReports()
    {
        return $this->Reports;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->ContentType;
    }
}
