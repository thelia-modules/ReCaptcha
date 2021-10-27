<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ReCaptcha\EventListeners;

use ReCaptcha\Event\ReCaptchaCheckEvent;
use ReCaptcha\Event\ReCaptchaEvents;
use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Contact\ContactEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

/**
 * This listener perfom captcha validation foir the standard contact form, by listening the  TheliaEvents::CONTACT_SUBMIT
 * event, and throwing a FormValidationException is the captcha could not be vaidated.
 */
class ContactFormListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        // Ensure comptatibility with pre-2.4 versions
        if (! defined('\Thelia\Core\Event\TheliaEvents::CONTACT_SUBMIT')
            ||
            false === (bool) ReCaptcha::getConfigValue('add_to_contact_form')
        ) {
            return [];
        }

        return [
            TheliaEvents::CONTACT_SUBMIT => [ 'validateCaptcha', 128 ]
        ];
    }

    public function validateCaptcha(ContactEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $checkCaptchaEvent = new ReCaptchaCheckEvent();

        $dispatcher->dispatch(ReCaptchaEvents::CHECK_CAPTCHA_EVENT, $checkCaptchaEvent);

        if (! $checkCaptchaEvent->isHuman()) {
            throw new FormValidationException(
                Translator::getInstance()->trans("Captcha validation failed, please try again.", [], ReCaptcha::DOMAIN_NAME)
            );
        }
    }
}
