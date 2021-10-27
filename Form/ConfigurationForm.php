<?php

namespace ReCaptcha\Form;

use ReCaptcha\ReCaptcha;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                "site_key",
                "text",
                [
                    "data" => ReCaptcha::getConfigValue("site_key"),
                    "label"=>Translator::getInstance()->trans("Site key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "site_key"],
                    "required" => true
                ]
            )
            ->add(
                "secret_key",
                "text",
                [
                    "data" => ReCaptcha::getConfigValue("secret_key"),
                    "label"=>Translator::getInstance()->trans("Secret key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "secret_key"],
                    "required" => true
                ]
            )
            ->add(
                "captcha_style",
                "choice",
                [
                    "data" => ReCaptcha::getConfigValue("captcha_style"),
                    "label"=>Translator::getInstance()->trans("ReCaptcha style", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "captcha_style"],
                    "required" => true,
                    'choices'  => [
                        'normal'=>'Normal',
                        'compact'=>'Compact',
                        'invisible'=>'Invisible'
                    ]
                ]
            );

        if (defined('\Thelia\Core\Event\TheliaEvents::CONTACT_SUBMIT')) {
            $this->formBuilder
                ->add(
                    "add_to_contact_form",
                    "checkbox",
                    [
                        "required" => false,
                        "data" => (bool) ReCaptcha::getConfigValue("add_to_contact_form"),
                        "value" => 1,
                        "label" => $this->translator->trans("Add captcha to standard contact form", [], ReCaptcha::DOMAIN_NAME),
                        "label_attr" => [
                            "for" => "add_to_contact_form",
                             'help' => $this->translator->trans("Check this box to add a captcha to the standard Thelia 2 contact form", [], ReCaptcha::DOMAIN_NAME)
                        ],

                    ]
                );
        }
    }

    public function getName()
    {
        return "recaptcha_configuration_form";
    }
}
