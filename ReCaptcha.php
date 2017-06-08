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

use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;

class ReCaptcha extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'recaptcha';

    /*
     * You may now override BaseModuleInterface methods, such as:
     * install, destroy, preActivation, postActivation, preDeactivation, postDeactivation
     *
     * Have fun !
     */

    public function getHooks()
    {
        return [
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "recaptcha.v2.button",
                "title" => [
                    "en_US" => "reCaptcha v2 button",
                    "fr_FR" => "Bouton reCaptcha v2",
                ],
                "block" => false,
                "active" => true,
            ],
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "recaptcha.invisible.button",
                "title" => [
                    "en_US" => "reCaptcha invisible button",
                    "fr_FR" => "Bouton reCaptcha invisible",
                ],
                "block" => false,
                "active" => true,
            ]
        ];
    }
}
