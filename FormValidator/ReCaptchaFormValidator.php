<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 11/12/2019 15:51
 */

namespace ReCaptcha\FormValidator;

use ReCaptcha\Event\ReCaptchaCheckEvent;
use ReCaptcha\Event\ReCaptchaEvents;
use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Thelia\Core\Form\TheliaFormValidator;
use Thelia\Form\BaseForm;
use Thelia\Form\Exception\FormValidationException;

class ReCaptchaFormValidator extends TheliaFormValidator
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator, $environment)
    {
        $this->dispatcher = $dispatcher;

        parent::__construct($translator, $environment);
    }

    public function validateForm(BaseForm $aBaseForm, $expectedMethod = null)
    {
        // Validate Captcha
        $checkCaptchaEvent = new ReCaptchaCheckEvent();

        $this->dispatcher->dispatch(ReCaptchaEvents::CHECK_CAPTCHA_EVENT, $checkCaptchaEvent);

        if (! $checkCaptchaEvent->isHuman()) {
            throw new FormValidationException($this->translator->trans("Sorry, it seems that you're not human.", [], ReCaptcha::DOMAIN_NAME));
        }

        return parent::validateForm($aBaseForm, $expectedMethod);
    }
}
