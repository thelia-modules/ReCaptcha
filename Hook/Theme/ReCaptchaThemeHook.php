<?php

declare(strict_types=1);

namespace ReCaptcha\Hook\Theme;

use ReCaptcha\Verifier\ReCaptchaVerifier;
use Thelia\Core\Hook\Theme\ThemeHookInterface;
use Twig\Environment;

/**
 * Loads the reCAPTCHA v3 script on every front page, so that any form carrying a
 * ReCaptchaType field gets its token filled in on submit.
 */
final readonly class ReCaptchaThemeHook implements ThemeHookInterface
{
    public function __construct(
        private Environment $twig,
        private ReCaptchaVerifier $verifier,
    ) {
    }

    public function supports(string $hookName): bool
    {
        return 'layout.body.bottom' === $hookName;
    }

    public function render(string $hookName, array $parameters): string
    {
        $siteKey = $this->verifier->getSiteKey();

        if (null === $siteKey) {
            return '';
        }

        return $this->twig->render('@ReCaptchaModule/theme-hook/script.html.twig', [
            'siteKey' => $siteKey,
        ]);
    }
}
