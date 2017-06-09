<?php

namespace ReCaptcha\Controller;

use ReCaptcha\ReCaptcha;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;

class ConfigurationController extends BaseAdminController
{
    public function viewAction()
    {
        return $this->render(
            "recaptcha/configuration"
        );
    }

    public function saveAction()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), 'ReCaptcha', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm("recaptcha_configuration.form");

        try {
            $data = $this->validateForm($form)->getData();

            ReCaptcha::setConfigValue('site_key', $data['site_key']);
            ReCaptcha::setConfigValue('secret_key', $data['secret_key']);

        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ReCaptcha::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->viewAction();
        }

        return $this->generateSuccessRedirect($form);
    }
}
