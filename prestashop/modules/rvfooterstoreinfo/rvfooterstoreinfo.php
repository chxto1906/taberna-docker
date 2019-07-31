<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Rvfooterstoreinfo extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'rvfooterstoreinfo';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('RV - Footer Store Information');
        $this->description = $this->l('Displays store logo and information.');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:rvfooterstoreinfo/views/templates/hook/rvfooterstoreinfo.tpl';
    }

    public function install()
    {
        if (file_exists(dirname(__FILE__).'/img/footer-logo.png'))
            Configuration::updateValue('RVFOOTERSTOREINFO_IMG', 'footer-logo.png');

        $text1 = array();
        foreach (Language::getLanguages(false) as $lang) {
            $text1[$lang['id_lang']] = 'Lorem Ipsum is not simply random text roots to popular belief It has roots in a piece of classical Lorem Ipsum is not simply random text root.';
        }
        Configuration::updateValue('RVFOOTERSTOREINFO_DESC', $text1);

        return parent::install() &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        Configuration::deleteByName('RVFOOTERSTOREINFO_IMG');
        Configuration::deleteByName('RVFOOTERSTOREINFO_DESC');

        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRvfooterstoreinfoModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Footer Logo Image'),
                        'name' => 'RVFOOTERSTOREINFO_IMG',
                        'thumb' => '../modules/'.$this->name.'/img/'.Configuration::get('RVFOOTERSTOREINFO_IMG'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Short Description of Store'),
                        'name' => 'RVFOOTERSTOREINFO_DESC',
                        'lang' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        if (!($languages = Language::getLanguages(true)))
            return false;

        $data = array(
            'RVFOOTERSTOREINFO_IMG' => Configuration::get('RVFOOTERSTOREINFO_IMG'),
        );

        foreach ($languages as $lang) {
            $data['RVFOOTERSTOREINFO_DESC'][$lang['id_lang']] = Configuration::get('RVFOOTERSTOREINFO_DESC', $lang['id_lang']);
        }

        return $data;
    }

    protected function postProcess()
    {
        if (((bool)Tools::isSubmit('submitRvfooterstoreinfoModule')) == true) {
            if (!($languages = Language::getLanguages(true))) {
                return false;
            }

            $text = array();
            foreach ($languages as $lang) {
                $text[$lang['id_lang']] = Tools::getValue('RVFOOTERSTOREINFO_DESC_'.$lang['id_lang']);
            }

            Configuration::updateValue('RVFOOTERSTOREINFO_DESC', $text);

            if (isset($_FILES['RVFOOTERSTOREINFO_IMG']) && isset($_FILES['RVFOOTERSTOREINFO_IMG']['tmp_name']) && !empty($_FILES['RVFOOTERSTOREINFO_IMG']['tmp_name'])) {
                if ($error = ImageManager::validateUpload($_FILES['RVFOOTERSTOREINFO_IMG'], 4000000)) {
                    return $error;
                } else {
                    $ext = Tools::substr($_FILES['RVFOOTERSTOREINFO_IMG']['name'], strrpos($_FILES['RVFOOTERSTOREINFO_IMG']['name'], '.') + 1);
                    $file_name = 'footer-logo'.'.'.$ext;
                    if (!move_uploaded_file($_FILES['RVFOOTERSTOREINFO_IMG']['tmp_name'], dirname(__FILE__).'/img/'.$file_name)) {
                        return $this->displayError($this->l('An error occurred while attempting to upload the file.'));
                    } else {
                        if (Configuration::hasContext('RVFOOTERSTOREINFO_IMG', null, Shop::getContext()) && Configuration::get('RVFOOTERSTOREINFO_IMG') != $file_name)
                            @unlink(dirname(__FILE__).'/img/'.Configuration::get('RVFOOTERSTOREINFO_IMG'));
                        Configuration::updateValue('RVFOOTERSTOREINFO_IMG', $file_name);
                        $this->_clearCache($this->templateFile);
                        return $this->displayConfirmation($this->l('The settings have been updated.'));
                    }
                }
            }
            $this->_clearCache($this->templateFile);
        }
        return '';
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }

            $this->smarty->assign($variables);
        }

        return $this->fetch($this->templateFile, $this->getCacheId(''));
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        $id_lang = $this->context->cart->id_lang;
        $imgname = Configuration::get('RVFOOTERSTOREINFO_IMG');
        return array(
            'rvfooterstoreinfo_img' => $this->context->link->protocol_content.Tools::getMediaServer($imgname).$this->_path.'img/'.$imgname,
            'rvfooterstoreinfo_desc' => Configuration::get('RVFOOTERSTOREINFO_DESC', $id_lang),
        );
    }
}
