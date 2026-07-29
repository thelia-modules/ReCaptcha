# Re Captcha

Protects Thelia front forms with Google reCAPTCHA v3.

The check is invisible: visitors never see a challenge or a "select all the traffic
lights" grid. Google scores each submission in the background, and the module
rejects those that score too low.

## Installation

```
composer require thelia/re-captcha-module
```

## Configuration

Create your keys on http://www.google.com/recaptcha/admin (reCAPTCHA **v3**), then
fill them in the back office, under `/admin/module/ReCaptcha`:

| Field | Purpose |
|-------|---------|
| Site key | public key, used on the front pages |
| Secret key | private key, used to validate submissions with Google |
| Captcha minimum score | below this score the submission is rejected (default `0.3`) |

The score runs from `0.0` (almost certainly a bot) to `1.0` (almost certainly a
human). Raise the minimum to filter harder, lower it if legitimate customers are
being turned away.

As long as the two keys are empty the module stays inert: the protected forms keep
working, with no captcha check at all. Nothing breaks on a store that has not
signed up with Google yet.

## THELIA 3 upgrade

The module adds a new “reCAPTCHA” field to the contact and login forms. You can do the same for third-party forms; see the next section.

### Client-side

On the front end, the module hooks into `theme_hook(‘layout.body.bottom’)`. It adds a JavaScript script that determines whether the user is a robot or not. If the user is indeed a human, it submits the form.

### Server-side

On the back end, the reCAPTCHA field is a `RecaptchaType` with a constraint that sends a request to Google.

## Protects the core front-end forms.

To protect another form, do not edit this class: declare the same listener from
your own bundle or module, or call `ReCaptchaProtection::apply()` directly in your
own form builder.

```php
     #[AsEventListener(event: TheliaEvents::FORM_AFTER_BUILD.‘.my_form’)]
     final class ProtectMyForm
     {
         public function __invoke(TheliaFormEvent $event): void
          {
              ReCaptchaProtection::apply($event->getForm()->getFormBuilder());
         }
     }
```
