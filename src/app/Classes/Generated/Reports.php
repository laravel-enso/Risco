<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class Reports implements JsonSerializable
{
    /**
     * @var int
     */
    private $JUST = null;

    /**
     * @var int
     */
    private $RAT = null;

    /**
     * @var int
     */
    private $RES = null;

    /**
     * @var int
     */
    private $LCO = null;

    /**
     * @var int
     */
    private $ACT = null;

    /**
     * @var int
     */
    private $ISACT = null;

    /**
     * @var int
     */
    private $BI = null;

    /**
     * @var int
     */
    private $CIP = null;

    /**
     * @var int
     */
    private $ONRC = null;

    /**
     * @var int
     */
    private $PIM = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'JUST'  => $this->JUST,
            'RAT'   => $this->RAT,
            'RES'   => $this->RES,
            'LCO'   => $this->LCO,
            'ACT'   => $this->ACT,
            'ISACT' => $this->ISACT,
            'BI'    => $this->BI,
            'CIP'   => $this->CIP,
            'ONRC'  => $this->ONRC,
            'PIM'   => $this->PIM,
        ];
    }

    /**
     * @return int
     */
    public function getJUST()
    {
        return $this->JUST;
    }

    /**
     * @return int
     */
    public function getRAT()
    {
        return $this->RAT;
    }

    /**
     * @return int
     */
    public function getRES()
    {
        return $this->RES;
    }

    /**
     * @return int
     */
    public function getLCO()
    {
        return $this->LCO;
    }

    /**
     * @return int
     */
    public function getACT()
    {
        return $this->ACT;
    }

    /**
     * @return int
     */
    public function getISACT()
    {
        return $this->ISACT;
    }

    /**
     * @return int
     */
    public function getBI()
    {
        return $this->BI;
    }

    /**
     * @return int
     */
    public function getCIP()
    {
        return $this->CIP;
    }

    /**
     * @return int
     */
    public function getONRC()
    {
        return $this->ONRC;
    }

    /**
     * @return int
     */
    public function getPIM()
    {
        return $this->PIM;
    }
}
