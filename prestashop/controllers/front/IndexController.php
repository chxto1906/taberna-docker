<?php
/**
 * 2007-2018 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class IndexControllerCore extends FrontController
{
    public $php_self = 'index';

    public $days = ["Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];

    /**
     * Assign template vars related to page content.
     *
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        if (Tools::getIsset("social")) {
            if ($this->validateSocial(Tools::getValue("social"))) {
                $this->process_social(Tools::getValue("social"));
            }
        }

        $this->context->smarty->assign(array(
            'HOOK_HOME' => Hook::exec('displayHome'),
        ));
        $this->setTemplate('index');
    }

    public function process_social($social) {
        $write = array();
        $write['date_time'] = date("Y-m-d H:i:s");
        $write['time'] = date("H:i:s");
        $write['day'] = $this->days[date("N")-1];
        $write['month'] = date("n");
        $write['year'] = date("Y");
        $write['format_date'] = date("d/m/Y H:i:s");
        $write['social'] = $social;

        Db::getInstance()->insert('social_count', $write);
    }

    public function validateSocial($social) {
        $result = false;
        if ($social == "fb" || $social == "ig") {
           $result = true; 
        }
        return $result;
    }
}
