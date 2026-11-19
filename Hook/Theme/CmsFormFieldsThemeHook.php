<?php

declare(strict_types=1);

namespace ReCaptcha\Hook\Theme;

use ReCaptcha\Form\Type\ReCaptchaType;
use ReCaptcha\Verifier\ReCaptchaVerifier;
use Thelia\Core\Hook\Theme\ThemeHookInterface;

/**
 * Adds the reCAPTCHA field to every form of Thelia CMS, through the hook its
 * form template renders inside the form. The script loaded on every page
 * (ReCaptchaThemeHook) fills it in on submit; CmsFormSubmissionListener checks it.
 *
 * Without Thelia CMS the hook is never rendered, and this class does nothing.
 */
final readonly class CmsFormFieldsThemeHook implements ThemeHookInterface
{
    /** FormPartial::FIELDS_HOOK of Thelia CMS, named here so that the module is not required. */
    public const string HOOK = 'cms.form.fields';

    public function __construct(
        private ReCaptchaVerifier $verifier,
    ) {
    }

    public function supports(string $hookName): bool
    {
        return self::HOOK === $hookName;
    }

    public function render(string $hookName, array $parameters): string
    {
        if (!$this->verifier->isConfigured()) {
            return '';
        }

        return \sprintf(
            '<input type="hidden" name="%s" class="g-recaptcha" data-sitekey="%s" value="">',
            ReCaptchaType::FIELD_NAME,
            htmlspecialchars((string) $this->verifier->getSiteKey(), \ENT_QUOTES | \ENT_HTML5),
        );
    }
}
