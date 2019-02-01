# Re Captcha

This module allow you to add easily a reCAPTCHA to your form
## Installation

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/re-captcha-module:~2.0.0
```

## Usage

Before using this module you have to create google api key here http://www.google.com/recaptcha/admin  
next configure your reCAPTCHA access here http://your_site.com`/admin/module/ReCaptcha` with keys you obtained in Google's page 
and choose which style of captcha you want :

- A standard captcha (or a compact version of this one)

    ![Checkbox captcha](https://developers.google.com/recaptcha/images/newCaptchaAnchor.gif)

- An invisible captcha
 
    ![Invisible captcha](https://developers.google.com/recaptcha/images/invisible_badge.png)


Then you'll need help from a developer to add some hooks in template and dispatch the check events, see details below.

### Hook

First if you don't have `{hook name="main.head-top"}` hook in your template you have to put this hook `{hook name="recaptcha.js"}` in the top of your head  
Then add this hook `{hook name="recaptcha.check"}` in every form where you want to check if the user is human,  
be careful if you want to use the invisible captcha this hook must be placed directly in the form tag like this :
```
<form id="form-contact" action="{url path="/contact"}" method="post">
    {hook name="recaptcha.check"}
    // End of the form
</form>
```

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
   
Don't forget to add this use at the top of your class :   
```
use ReCaptcha\Event\ReCaptchaCheckEvent;   
use ReCaptcha\Event\ReCaptchaEvents;
```
