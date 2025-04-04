<?php

namespace ReCaptcha\Action;

use ReCaptcha\Event\ReCaptchaCheckEvent;
use ReCaptcha\Event\ReCaptchaEvents;
use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Contact\ContactEvent;
use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\Event\NewsletterEvent;

class ReCaptchaAction implements EventSubscriberInterface
{
    /** @var  Request */
    protected $request;

    public function __construct(RequestStack $requestStack, private readonly EventDispatcherInterface $eventDispatcher)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function checkCaptcha(ReCaptchaCheckEvent $event)
    {
        $requestUrl = "https://www.google.com/recaptcha/api/siteverify";

        $secretKey = ReCaptcha::getConfigValue('secret_key');
        $minScore = ReCaptcha::getConfigValue('min_score', 0.3);
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
        if ($result['success'] == true && (!array_key_exists('score', $result) || $result['score'] > $minScore)) {
            $event->setHuman(true);
        }
    }

    public function sendCaptchaEvent(): void
    {
        $checkCaptchaEvent = new ReCaptchaCheckEvent();
        $this->eventDispatcher->dispatch($checkCaptchaEvent, ReCaptchaEvents::CHECK_CAPTCHA_EVENT);

        if ($checkCaptchaEvent->isHuman() === false) {
            throw new FormValidationException('Invalid captcha');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ReCaptchaEvents::CHECK_CAPTCHA_EVENT => ['checkCaptcha', 128],
            TheliaEvents::NEWSLETTER_SUBSCRIBE => ['sendCaptchaEvent', 256],
            TheliaEvents::CUSTOMER_CREATEACCOUNT => ['sendCaptchaEvent', 256],
            TheliaEvents::CONTACT_SUBMIT => ['sendCaptchaEvent', 256],
        ];
    }
}
