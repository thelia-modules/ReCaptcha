<?php


namespace ReCaptcha\Hook;

use ReCaptcha\ReCaptcha;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class HookManager extends BaseHook
{
    public function onModuleConfigure(HookRenderEvent $event)
    {
        $event->add(
            $this->render(
                'recaptcha/configuration.html',
                [
                    'with_contact_form' => defined('\Thelia\Core\Event\TheliaEvents::CONTACT_SUBMIT')
                ]
            )
        );
    }

    public function addRecaptchaCheckContact(HookRenderEvent $event)
    {
        // Ensure comptatibility with pre-2.4 versions
        if (defined('\Thelia\Core\Event\TheliaEvents::CONTACT_SUBMIT')
            &&
            (bool) ReCaptcha::getConfigValue('add_to_contact_form')
        ) {
            $this->addRecaptchaCheck($event);
        }
    }

    public function addRecaptchaCheck(HookRenderEvent $event)
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');
        $captchaStyle = ReCaptcha::getConfigValue('captcha_style');

        $captchaId= "recaptcha";
        $captchaCallback = "";
        if ($captchaStyle === 'invisible') {
            $captchaCallback = "data-callback='onCompleted'";
            $captchaId .= '-invisible';
        }

        $event->add("<div id='$captchaId' class='g-recaptcha' data-sitekey='$siteKey' $captchaCallback data-size='$captchaStyle'></div>");
    }


}
