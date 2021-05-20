<?php

namespace ReCaptcha\Action;

use ReCaptcha\Event\ReCaptchaCheckEvent;
use ReCaptcha\Event\ReCaptchaEvents;
use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ReCaptchaAction implements EventSubscriberInterface
{
    /** @var  Request */
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function checkCaptcha(ReCaptchaCheckEvent $event)
    {
        $requestUrl = "https://www.google.com/recaptcha/api/siteverify";

        $secretKey = ReCaptcha::getConfigValue('secret_key');
        $requestUrl .= "?secret=$secretKey";

        $captchaResponse = $event->getCaptchaResponse();
        if (null == $captchaResponse) {
            $captchaResponse = $this->request->request->get('g-recaptcha-response');
        }

        $requestUrl .= "&response=$captchaResponse";

        $remoteIp = $event->getRemoteIp();
        if (null == $remoteIp) {
            $remoteIp = $this->request->server->get('REMOTE_ADDR');
        }

        $requestUrl .= "&remoteip=$remoteIp";

        $result = json_decode(file_get_contents($requestUrl), true);

        if ($result['success'] == true) {
            $event->setHuman(true);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ReCaptchaEvents::CHECK_CAPTCHA_EVENT => ['checkCaptcha', 128],
        ];
    }
}
