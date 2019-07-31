<?php
/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once(_PS_MODULE_DIR_ . 'rvpopupemail/lib/RvPopupEmailCore.php');
include_once(_PS_MODULE_DIR_ . 'rvpopupemail/lib/RvEmailSubscription.php');

class RvPopupEmail extends RvPopupEmailCore
{
    private $templateFile;
    public function __construct()
    {
        $this->name = 'rvpopupemail';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('RV - Email Subscriber');
        $this->description = $this->l('Configure Popup Email Subscriber elements of your theme\'');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:rvpopupemail/views/templates/hook/rvpopupemail.tpl';
    }
    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader');
    }
    public function uninstall()
    {
        return parent::uninstall();
    }
    public function hookDisplayHeader()
    {
        $id_shop = $this->context->shop->id;
        /*general */
        $this->context->smarty->assign(array(
            'RV_BANNER_EMAILSUB' => $this->getLinkFile($this->getValueConfig('Banner_Popup_Emailsub')),
            'RV_TITLE_EMAILSUB' => $this->getValueConfig('Title_Popup_Emailsub'),
            'RV_DESCRIPTION_EMAILSUB' => $this->getValueConfig('Description_Popup_Emailsub'),
            'DISPLAY_EMAILSUBCRIPTION' => $this->getValueConfig('Display_Emailsubscription'),
            'URL_AJAX_EMALISUBSCRIPTION' => $this->context->link->getModuleLink($this->name, 'ajaxemailsubcription'),
        ));

        /*load library jquery*/

        $this->context->controller->addJs($this->_path.'views/js/front/custom.js');

        $this->context->controller->addJs($this->_path.'views/js/front/jquery.cookie.min.js');

         return $this->fetch($this->templateFile);
    }

    public function getValueConfig($key = null,$lang = false)
    {
        $lang_id = $this->context->language->id;
        $strConfig = Configuration::get($key,$lang_id);
        return $strConfig;
    }
    
    public function getLinkFile($file){
        if ($file && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file)) {
            return $this->context->link->protocol_content . Tools::getMediaServer($file) . $this->_path . 'img/' . $file;
        }else{
            return false;
        }
    }
}
