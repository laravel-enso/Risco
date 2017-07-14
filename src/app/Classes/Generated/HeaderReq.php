<?php

namespace LaravelEnso\Risco\app\Classes\Generated;

use JsonSerializable;

class HeaderReq implements JsonSerializable
{
    /**
     * @var string
     */
    private $channel = null;

    /**
     * @var string
     */
    private $extref = null;

    /**
     * @var string
     */
    private $intref = null;

    /**
     * @var string
     */
    private $daterequest = null;

    /**
     * @var string
     */
    private $dateresponse = null;

    /**
     * @var string
     */
    private $psign = null;

    /**
     * @var string
     */
    private $user = null;

    /**
     * @var string
     */
    private $password = null;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'channel' => $this->channel,
            'extref' => $this->extref,
            'intref' => $this->intref,
            'daterequest' => $this->daterequest,
            'dateresponse' => $this->dateresponse,
            'psign' => $this->psign,
            'user' => $this->user,
            'password' => $this->password,
        ];
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getExtref()
    {
        return $this->extref;
    }

    /**
     * @return string
     */
    public function getIntref()
    {
        return $this->intref;
    }

    /**
     * @return string
     */
    public function getDaterequest()
    {
        return $this->daterequest;
    }

    /**
     * @return string
     */
    public function getDateresponse()
    {
        return $this->dateresponse;
    }

    /**
     * @return string
     */
    public function getPsign()
    {
        return $this->psign;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
