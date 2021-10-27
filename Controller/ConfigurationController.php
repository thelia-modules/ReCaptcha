<?php

namespace ReCaptcha\Controller;

use ReCaptcha\ReCaptcha;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;

class ConfigurationController extends BaseAdminController
{
    public function saveAction()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), 'ReCaptcha', AccessManager::VIEW)) {
            return $response;
        }

        $saveMode = $this->getRequest()->request->get("save_mode");

        $form = $this->createForm("recaptcha_configuration.form");

        try {
            $data = $this->validateForm($form)->getData();

            ReCaptcha::setConfigValue('site_key', $data['site_key']);
            ReCaptcha::setConfigValue('secret_key', $data['secret_key']);
            ReCaptcha::setConfigValue('captcha_style', $data['captcha_style']);
            ReCaptcha::setConfigValue('add_to_contact_form', $data['add_to_contact_form'] ? 1 : 0);

            if ($saveMode !== 'stay') {
                return $this->generateRedirect(URL::getInstance()->absoluteUrl('/admin/modules'));
            }
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
        }

        return $this->generateRedirect(URL::getInstance()->absoluteUrl('/admin/module/ReCaptcha'));
    }
}
