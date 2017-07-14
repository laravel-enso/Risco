<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class Financial_Res implements JsonSerializable
{

    /**
     * @var FIN_Res
     */
    private $FIN_Res = null;

    /**
     * @var IID_Res
     */
    private $IID_Res = null;

    /**
     * @var STS_Res
     */
    private $STS_Res = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'FIN_Res' => $this->FIN_Res,
            'IID_Res' => $this->IID_Res,
            'STS_Res' => $this->STS_Res,
        ];
    }

    /**
     * @return FIN_Res
     */
    public function getFIN_Res()
    {
        return $this->FIN_Res;
    }

    /**
     * @return IID_Res
     */
    public function getIID_Res()
    {
        return $this->IID_Res;
    }

    /**
     * @return STS_Res
     */
    public function getSTS_Res()
    {
        return $this->STS_Res;
    }


}

