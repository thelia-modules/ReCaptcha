<?php

declare(strict_types=1);

namespace ReCaptcha\EventListener;

use ReCaptcha\Form\Type\ReCaptchaType;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Verifier\ReCaptchaVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TheliaCMS\Form\SubmissionCheckEvent;

/**
 * Checks the reCAPTCHA token of a message sent through a form of Thelia CMS,
 * which the CMS asks about once the message is otherwise worth keeping.
 *
 * The event is named by a string: without Thelia CMS it is never dispatched,
 * and nothing here is loaded.
 */
final readonly class CmsFormSubmissionListener implements EventSubscriberInterface
{
    /** SubmissionCheckEvent::class of Thelia CMS. */
    public const string EVENT = 'TheliaCMS\Form\SubmissionCheckEvent';

    public function __construct(
        private ReCaptchaVerifier $verifier,
        private TranslatorInterface $translator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [self::EVENT => 'check'];
    }

    public function check(SubmissionCheckEvent $event): void
    {
        // Keys not entered yet: the forms keep working, as the other protected forms do.
        if (!$this->verifier->isConfigured()) {
            return;
        }

        $token = $event->request->request->get(ReCaptchaType::FIELD_NAME);

        if ($this->verifier->verify(\is_string($token) ? $token : null, $event->request->getClientIp())) {
            return;
        }

        $event->refuse($this->translator->trans(
            'We could not check that you are not a robot. Please send your message again.',
            [],
            ReCaptcha::DOMAIN_NAME,
        ));
    }
}
