<?php

namespace ReCaptcha\EventListener;

use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Form\Definition\FrontForm;

class ContactFormEventListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [TheliaEvents::FORM_AFTER_BUILD.'.thelia_contact' => ['addRecaptchaField', 300]];
    }

    public function addRecaptchaField(TheliaFormEvent $event) : void
    {
        $event->getForm()->getFormBuilder()
            ->add(
                'g-recaptcha-response',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                       'id' => 'g-recaptcha-response',
                       'class' => 'g-recaptcha',
                       'data-sitekey' => ReCaptcha::getConfigValue('site_key'),
                       'data-controller' => 'recaptcha',
                       'data-script' => ReCaptcha::RECAPTCHA_API_URL.'?render='.ReCaptcha::getConfigValue('site_key'),
                   ]
                ]
            )
        ;

    }
}
