<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class Rapoarte_Res implements JsonSerializable
{
    /**
     * @var JUST_Res
     */
    private $JUST_Res = null;

    /**
     * @var RAT_Res
     */
    private $RAT_Res = null;

    /**
     * @var RES_Res
     */
    private $RES_Res = null;

    /**
     * @var LCO_Res
     */
    private $LCO_Res = null;

    /**
     * @var ACT_Res
     */
    private $ACT_Res = null;

    /**
     * @var ISACT_Res
     */
    private $ISACT_Res = null;

    /**
     * @var ONRC_Res
     */
    private $ONRC_Res = null;

    /**
     * @var BI_Res
     */
    private $BI_Res = null;

    /**
     * @var CIP_Res
     */
    private $CIP_Res = null;

    /**
     * @var PIM_Res
     */
    private $PIM_Res = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'JUST_Res' => $this->JUST_Res,
            'RAT_Res' => $this->RAT_Res,
            'RES_Res' => $this->RES_Res,
            'LCO_Res' => $this->LCO_Res,
            'ACT_Res' => $this->ACT_Res,
            'ISACT_Res' => $this->ISACT_Res,
            'ONRC_Res' => $this->ONRC_Res,
            'BI_Res' => $this->BI_Res,
            'CIP_Res' => $this->CIP_Res,
            'PIM_Res' => $this->PIM_Res,
        ];
    }

    /**
     * @return JUST_Res
     */
    public function getJUST_Res()
    {
        return $this->JUST_Res;
    }

    /**
     * @return RAT_Res
     */
    public function getRAT_Res()
    {
        return $this->RAT_Res;
    }

    /**
     * @return RES_Res
     */
    public function getRES_Res()
    {
        return $this->RES_Res;
    }

    /**
     * @return LCO_Res
     */
    public function getLCO_Res()
    {
        return $this->LCO_Res;
    }

    /**
     * @return ACT_Res
     */
    public function getACT_Res()
    {
        return $this->ACT_Res;
    }

    /**
     * @return ISACT_Res
     */
    public function getISACT_Res()
    {
        return $this->ISACT_Res;
    }

    /**
     * @return ONRC_Res
     */
    public function getONRC_Res()
    {
        return $this->ONRC_Res;
    }

    /**
     * @return BI_Res
     */
    public function getBI_Res()
    {
        return $this->BI_Res;
    }

    /**
     * @return CIP_Res
     */
    public function getCIP_Res()
    {
        return $this->CIP_Res;
    }

    /**
     * @return PIM_Res
     */
    public function getPIM_Res()
    {
        return $this->PIM_Res;
    }
}
