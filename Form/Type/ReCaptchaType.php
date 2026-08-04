<?php

declare(strict_types=1);

namespace ReCaptcha\Form\Type;

use ReCaptcha\ReCaptcha;
use ReCaptcha\Validator\ValidReCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ReCaptchaType extends AbstractType
{
    public const string FIELD_NAME = 'g-recaptcha';

    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'recaptcha';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'mapped' => false,
                'required' => false,
                'constraints' => [new ValidReCaptcha()],
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');

        if (empty($siteKey)) {
            return;
        }

        $view->vars['attr'] = array_merge($view->vars['attr'], [
            'data-sitekey' => $siteKey,
            'class' => 'g-recaptcha'
        ]);
    }
}
