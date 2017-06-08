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
            );
    }

    public function getName()
    {
        return "recaptcha_configuration_form";
    }
}
