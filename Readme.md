# Re Captcha

This module allow you to add easily a reCAPTCHA to yout form
## Installation

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/re-captcha-module:~1.0
```

## Usage

Before using this module you have to create google api key here http://www.google.com/recaptcha/admin
next configure your reCAPTCHA access here /admin/module/ReCaptcha`
then you'll need help from a developer to add some hooks in template and dispatch check event

### Hook

First if you don't have `{hook name="main.head-top"}` hook in your template you have to put a hook `{hook name="recaptcha.js"}` in the top of your head
After that you have the choice between two type of captcha :

1) A standard captcha

![Checkbox captcha](https://developers.google.com/recaptcha/images/newCaptchaAnchor.gif)

In this case you have just to put this hook `{hook name="recaptcha.v2.button"}` in form where you want to use captcha

2) The new invisible captcha

![Invisible captcha](https://developers.google.com/recaptcha/images/invisible_badge.png)

In this case you'll have a bit more to do :

* Add this class `captcha-form` to the form
* Add this class `g-recaptcha` to the submit button and this hook `{hook name="recaptcha.invisible.button"}` in the tag like this `<button class="btn btn-primary g-recaptcha" {hook name="recaptcha.invisible.button"}>Validate</button>` 

### Event

To check in server-side if the captcha is valid you have to dispatch the "CHECK_CAPTCHA_EVENT" like this :
```
$checkCaptchaEvent = new ReCaptchaCheckEvent();
$this->dispatch(ReCaptchaEvents::CHECK_CAPTCHA_EVENT, $checkCaptchaEvent);
```

Then the result of check is available in `$checkCaptchaEvent->isHuman()`as boolean so you can do a test like this :
```
if ($checkCaptchaEvent->isHuman() == false) {
    throw new \Exception('Invalid captcha');
}
```