<?php

namespace ReCaptcha\Event;

use Thelia\Core\Event\ActionEvent;

class ReCaptchaCheckEvent extends ActionEvent
{
    protected $captchaResponse = null;

    protected $remoteIp = null;

    /** @var boolean */
    protected $human = false;

    public function __construct($captchaResponse = null, $remoteIp = null)
    {
        if (null !== $captchaResponse) {
            $this->captchaResponse = $captchaResponse;
        }

        if (null !== $remoteIp) {
            $this->remoteIp = $remoteIp;
        }
    }

    /**
     * @return null
     */
    public function getCaptchaResponse()
    {
        return $this->captchaResponse;
    }

    /**
     * @param null $captchaResponse
     * @return ReCaptchaCheckEvent
     */
    public function setCaptchaResponse($captchaResponse)
    {
        $this->captchaResponse = $captchaResponse;
        return $this;
    }

    /**
     * @return null
     */
    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

    /**
     * @param null $remoteIp
     * @return ReCaptchaCheckEvent
     */
    public function setRemoteIp($remoteIp)
    {
        $this->remoteIp = $remoteIp;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHuman()
    {
        return $this->human;
    }

    /**
     * @param bool $human
     * @return ReCaptchaCheckEvent
     */
    public function setHuman($human)
    {
        $this->human = $human;
        return $this;
    }
}
