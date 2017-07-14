<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

class ServiceReq
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

