<?php

declare(strict_types=1);

namespace ReCaptcha\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Marks a field as carrying a reCAPTCHA v3 token to be verified against Google.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ValidReCaptcha extends Constraint
{
    public string $message = 'An error has occurred, please try again.';
}
