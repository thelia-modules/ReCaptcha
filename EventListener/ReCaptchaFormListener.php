<?php

declare(strict_types=1);

namespace ReCaptcha\EventListener;

use ReCaptcha\Form\ReCaptchaProtection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;

/**
 * Protects the core front forms.
 */
final class ReCaptchaFormListener implements EventSubscriberInterface
{
    /** Names returned by BaseForm::getName(), which is what suffixes the event. */
    private const array PROTECTED_FORMS = [
        'thelia_contact',
        'thelia_customer_login',
    ];

    public static function getSubscribedEvents(): array
    {
        $events = [];

        foreach (self::PROTECTED_FORMS as $formName) {
            $events[TheliaEvents::FORM_AFTER_BUILD.'.'.$formName] = ['addCaptchaField', 128];
        }

        return $events;
    }

    public function addCaptchaField(TheliaFormEvent $event): void
    {
        ReCaptchaProtection::apply($event->getForm()->getFormBuilder());
    }
}
