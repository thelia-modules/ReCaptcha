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
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                "site_key",
                TextType::class,
                [
                    "data" => ReCaptcha::getConfigValue("site_key"),
                    "label"=>Translator::getInstance()->trans("Site key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "site_key"],
                    "required" => true
                ]
            )
            ->add(
                "secret_key",
                TextType::class,
                [
                    "data" => ReCaptcha::getConfigValue("secret_key"),
                    "label"=>Translator::getInstance()->trans("Secret key", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "secret_key"],
                    "required" => true
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
            )
            ->add(
                "captcha_style",
                ChoiceType::class,
                [
                    "data" => ReCaptcha::getConfigValue("captcha_style"),
                    "label"=>Translator::getInstance()->trans("ReCaptcha style", array(), ReCaptcha::DOMAIN_NAME),
                    "label_attr" => ["for" => "captcha_style"],
                    "required" => true,
                    'choices'  => [
                        'Normal'=>'normal',
                        'Compact'=>'compact',
                        'Invisible'=>'invisible'
                    ]
                ]
            );
    }

    public static function getName()
    {
        return "recaptcha_configuration_form";
    }
}
