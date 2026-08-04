<?php

declare(strict_types=1);

namespace ReCaptcha\Form;

use ReCaptcha\Form\Type\ReCaptchaType;
use Symfony\Component\Form\FormBuilderInterface;

final class ReCaptchaProtection
{
    public static function apply(FormBuilderInterface $builder): void
    {
        if ($builder->has(ReCaptchaType::FIELD_NAME)) {
            return;
        }

        $builder->add(ReCaptchaType::FIELD_NAME, ReCaptchaType::class);
    }
}
