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

class RvPaymentLogo extends Module implements WidgetInterface
{
	private $templateFile;
	public function __construct()
	{
		$this->name = 'rvpaymentlogo';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'RV Templates';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('RV - Payment logos block');
		$this->description = $this->l('Adds a block which displays all of your payment logos.');
		$this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

		$this->templateFile = 'module:rvpaymentlogo/views/templates/hook/rvpaymentlogo.tpl';
	}

	public function install()
	{
		Configuration::updateValue('RV_PAYMENT_LOGO_CMS_ID', 1);
		return (parent::install() 
				&& $this->registerHook('displayProductPage')
			    && $this->registerHook('displayFooterAfter'));
	}

	public function uninstall()
	{
		Configuration::deleteByName('RV_PAYMENT_LOGO_CMS_ID');
		return parent::uninstall();
	}

	public function getContent()
	{
		$html = '';

		if (Tools::isSubmit('submitConfiguration'))
			if (Validate::isUnsignedInt(Tools::getValue('RV_PAYMENT_LOGO_CMS_ID')))
			{
				Configuration::updateValue('RV_PAYMENT_LOGO_CMS_ID', (int)(Tools::getValue('RV_PAYMENT_LOGO_CMS_ID')));
				$this->_clearCache('*');
				$html .= $this->displayConfirmation($this->l('The settings have been updated.'));
			}

		$cmss = CMS::listCms($this->context->language->id);

		if (!count($cmss))
			$html .= $this->displayError($this->l('No CMS page is available.'));
		else
			$html .= $this->renderForm();

		return $html;
	}


	public function renderWidget($hookName = null, array $configuration = array())
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {
            
            if (!Configuration::get('RV_PAYMENT_LOGO_CMS_ID')) {
				return;
	        }
            
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
        if (Configuration::get('PS_CATALOG_MODE')) {
			return;
        }

        $cms = new CMS(Configuration::get('RV_PAYMENT_LOGO_CMS_ID'), $this->context->language->id);
		if (!Validate::isLoadedObject($cms)) {
			return;
		}
        
        return array(
            'cms_payment_logo' => $cms,
            'path' => $this->_path,
        );
    }

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Destination page for the block\'s link'),
						'name' => 'RV_PAYMENT_LOGO_CMS_ID',
						'required' => false,
						'default_value' => (int)$this->context->country->id,
						'options' => array(
							'query' => CMS::listCms($this->context->language->id),
							'id' => 'id_cms',
							'name' => 'meta_title'
						)
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitConfiguration';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'RV_PAYMENT_LOGO_CMS_ID' => Tools::getValue('RV_PAYMENT_LOGO_CMS_ID', Configuration::get('RV_PAYMENT_LOGO_CMS_ID')),
		);
	}

}


