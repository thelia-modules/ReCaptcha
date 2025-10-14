<?php

namespace ReCaptcha\Action;

use ReCaptcha\Event\ReCaptchaCheckEvent;
use ReCaptcha\Event\ReCaptchaEvents;
use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request as TheliaRequest;
use Thelia\Form\Exception\FormValidationException;

class ReCaptchaAction implements EventSubscriberInterface
{
    public function __construct(protected RequestStack $requestStack, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function checkCaptcha(ReCaptchaCheckEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (TheliaRequest::$isAdminEnv) {
            return;
        }

        $requestUrl = "https://www.google.com/recaptcha/api/siteverify";

        $secretKey = ReCaptcha::getConfigValue('secret_key');
        $minScore = ReCaptcha::getConfigValue('min_score', 0.3);
        $requestUrl .= "?secret=$secretKey";

        $captchaResponse = $event->getCaptchaResponse();
        if (null == $captchaResponse) {
            $captchaResponse = $request->request->get('g-recaptcha-response');
        }

        $requestUrl .= "&response=$captchaResponse";

        $remoteIp = $event->getRemoteIp();
        if (null == $remoteIp) {
            $remoteIp = $request->server->get('REMOTE_ADDR');
        }

        $requestUrl .= "&remoteip=$remoteIp";

        $result = json_decode(file_get_contents($requestUrl), true);
        if ($result['success'] && (!array_key_exists('score', $result) || $result['score'] > $minScore)) {
            $event->setHuman(true);
        }
    }

    public function sendCaptchaEvent(): void
    {
        if (TheliaRequest::$isAdminEnv) {
            return;
        }

        $checkCaptchaEvent = new ReCaptchaCheckEvent();
        $this->eventDispatcher->dispatch($checkCaptchaEvent, ReCaptchaEvents::CHECK_CAPTCHA_EVENT);

        if ($checkCaptchaEvent->isHuman() === false) {
            throw new FormValidationException('Invalid captcha');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ReCaptchaEvents::CHECK_CAPTCHA_EVENT => ['checkCaptcha', 128],
            TheliaEvents::NEWSLETTER_SUBSCRIBE => ['sendCaptchaEvent', 256],
            TheliaEvents::CUSTOMER_CREATEACCOUNT => ['sendCaptchaEvent', 256],
            TheliaEvents::CONTACT_SUBMIT => ['sendCaptchaEvent', 256],
        ];
    }
}
