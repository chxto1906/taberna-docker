<?php
/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

class RvPopupEmailAjaxEmailSubcriptionModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        $action = Tools::getValue('action');
        $email = Tools::getValue('email');
        $emailsubscription = new RvEmailSubscription();
        $result = $emailsubscription->newsletterRegistration($email,$action);
        ob_end_clean();
        die(Tools::jsonEncode($result));
    }       

}
