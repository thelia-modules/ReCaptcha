<?php

namespace ReCaptcha\Form;

use ReCaptcha\ReCaptcha;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;


class ConfigurationForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add(
                "site_key",
                TextType::class,
                [
                    "data" => ReCaptcha::getConfigValue("site_key"),
                    "label"=>Translator::getInstance()->trans("Site key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "site_key"],
                    "required" => true,
                    "row_attr" => [
                        "class" => 'col-sm-6'
                    ]
                ]
            )
            ->add(
                "secret_key",
                TextType::class,
                [
                    "data" => ReCaptcha::getConfigValue("secret_key"),
                    "label"=>Translator::getInstance()->trans("Secret key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "secret_key"],
                    "required" => true,
                    "row_attr" => [
                        "class" => 'col-sm-6'
                    ]
                ]
            )
            ->add(
                "min_score",
                NumberType::class,
                [
                    "data" => ReCaptcha::getConfigValue("min_score"),
                    "label"=>Translator::getInstance()->trans("Captcha minimum score", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "min_score"],
                    "required" => true,
                    "attr" => [
                        "min" => 0.1,
                        "max" => 1,
                        "step" => 0.1
                    ]
                ]
            );
    }

    public static function getName() : string
    {
        return "recaptcha_configuration_form";
    }
}
