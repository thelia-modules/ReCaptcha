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
        $type = "";

        if ($captchaStyle === 'invisible') {
            $captchaCallback = "data-callback='onCompleted'";
            $type = "g-invisible";
            $captchaId .= '-invisible';
        }

        if (null !== $event->getArgument('id')) {
            $captchaId = $event->getArgument('id');
        }

        $event->add("<div id='$captchaId' class='g-recaptcha $type' data-sitekey='$siteKey' $captchaCallback data-size='$captchaStyle'></div>");
    }

    public function loadRecaptcha(HookRenderEvent $event)
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');
        $captchaStyle = ReCaptcha::getConfigValue('captcha_style');

        if ($captchaStyle !== 'invisible') {
            $event->add($this->render(
                'recaptcha-js.html',
                [
                    "siteKey" => $siteKey,
                    "captchaStyle" => $captchaStyle,
                ]
            ));

            return;
        }

        $event->add($this->render(
            'recaptcha-js-invisible.html',
            [
                "siteKey" => $siteKey,
                "captchaStyle" => $captchaStyle,
            ]
        ));
    }
}
