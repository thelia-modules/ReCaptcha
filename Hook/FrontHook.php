<?php


namespace ReCaptcha\Hook;


use ReCaptcha\ReCaptcha;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
    public function onRecaptchaV2Button(HookRenderEvent $event)
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');

        $event->add("<div class='g-recaptcha' data-sitekey='$siteKey'></div>");
    }

    public function onRecaptchaInvisibleButton(HookRenderEvent $event)
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');

        $event->add(" data-sitekey='$siteKey' data-callback='onCaptchaSubmit' ");
    }
}
