<?php


namespace ReCaptcha\Hook;

use ReCaptcha\ReCaptcha;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
    public function addRecaptchaCheck(HookRenderEvent $event)
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');
        $captchaStyle = ReCaptcha::getConfigValue('captcha_style');

        $captchaId= "recaptcha";
        $captchaCallback = "";
        $type = "";

        if ($captchaStyle === 'invisible') {
            $captchaCallback = "data-callback='onCompleted'";
            $type = "g-invisble";
            $captchaId = $captchaId.'-invisible';
        }

        if (null !== $event->getArgument('id')) {
            $captchaId = $event->getArgument('id');
        }

        $event->add("<div id='$captchaId' class='g-recaptcha $type' data-sitekey='$siteKey' $captchaCallback data-size='$captchaStyle'></div>");
    }
}
