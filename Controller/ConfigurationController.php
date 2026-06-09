<?php

namespace ReCaptcha\Controller;

use ReCaptcha\Form\ConfigurationForm;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Form\TheliaFormFactory;
use Twig\Environment;

class ConfigurationController extends BaseAdminController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly TheliaFormFactory $formFactory,
    ) {
    }

    public function viewAction(): Response
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ReCaptcha', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->formFactory->createForm(ConfigurationForm::getName());

        return new Response($this->twig->render(
            '@ReCaptchaModule/backOffice/default-twig/recaptcha/configuration.html.twig',
            ['form' => $form->createView()->getView()]
        ));
    }

    public function saveAction(): Response
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ReCaptcha', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->formFactory->createForm(ConfigurationForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            ReCaptcha::setConfigValue('site_key', $data['site_key']);
            ReCaptcha::setConfigValue('secret_key', $data['secret_key']);
            ReCaptcha::setConfigValue('min_score', $data['min_score']);
            ReCaptcha::setConfigValue('captcha_style', $data['captcha_style']);
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans('Error', [], ReCaptcha::DOMAIN_NAME),
                $e->getMessage(),
                $form
            );

            return new Response($this->twig->render(
                '@ReCaptchaModule/backOffice/default-twig/recaptcha/configuration.html.twig',
                [
                    'form' => $form->createView()->getView(),
                    'form_error_message' => $e->getMessage(),
                ]
            ));
        }

        return $this->generateSuccessRedirect($form);
    }
}
