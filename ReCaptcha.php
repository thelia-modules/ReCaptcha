<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ReCaptcha;

use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;

class ReCaptcha extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'recaptcha';
    const RECAPTCHA_API_URL = 'https://www.google.com/recaptcha/api.js';
    /*
     * You may now override BaseModuleInterface methods, such as:
     * install, destroy, preActivation, postActivation, preDeactivation, postDeactivation
     *
     * Have fun !
     */

    public function getHooks() : array
    {
        return [
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "recaptcha.js",
                "title" => [
                    "en_US" => "reCaptcha js",
                    "fr_FR" => "Js pour recaptcha",
                ],
                "block" => false,
                "active" => true,
            ],
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "recaptcha.check",
                "title" => [
                    "en_US" => "reCaptcha check hook",
                    "fr_FR" => "reCaptcha check hook",
                ],
                "block" => false,
                "active" => true,
            ],
        ];
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR . ucfirst(self::getModuleCode()). "/I18n/*"])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
