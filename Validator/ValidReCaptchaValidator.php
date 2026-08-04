<?php

declare(strict_types=1);

namespace ReCaptcha\Validator;

use ReCaptcha\ReCaptcha;
use ReCaptcha\Verifier\ReCaptchaVerifier;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Thelia\Core\HttpFoundation\Request as TheliaRequest;
use Thelia\Core\Translation\Translator;

/**
 * Thelia forms are built by BaseForm with Validation::createValidatorBuilder(), whose
 * default ConstraintValidatorFactory instantiates validators with `new $class()`. The
 * verifier therefore needs a default: without one, every submission of a protected form
 * dies on an ArgumentCountError. The container still injects the shared instance when
 * the validator is resolved through it.
 */
final class ValidReCaptchaValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ReCaptchaVerifier $verifier = new ReCaptchaVerifier(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidReCaptcha) {
            throw new UnexpectedTypeException($constraint, ValidReCaptcha::class);
        }

        if (TheliaRequest::$isAdminEnv || !$this->verifier->isConfigured()) {
            return;
        }

        if ($this->verifier->verify(\is_string($value) ? $value : null)) {
            return;
        }

        $this->context
            ->buildViolation(Translator::getInstance()->trans(
                $constraint->message,
                [],
                ReCaptcha::DOMAIN_NAME,
            ))
            ->addViolation();
    }
}
