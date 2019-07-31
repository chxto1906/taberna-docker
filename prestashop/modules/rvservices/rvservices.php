<?php
/**
* 2007-2018 PrestaShop
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
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once _PS_MODULE_DIR_.'rvservices/RvServiceClass.php';

class RvServices extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'rvservices';
        $this->tab = 'front_office_features';
        $this->author = 'RV Templates';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('RV - Services Block');
        $this->description = $this->l('Allows you to display service offered by your store.');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:rvservices/views/templates/hook/rvservices.tpl';
    }

    public function install()
    {
        return parent::install()
        && $this->installDB()
        && $this->installSampleData()
        && $this->registerHook('displayHome');
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvservices` (
            `id_service` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_shop` int(10) unsigned NOT NULL ,
            `file_name` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id_service`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvservices_lang` (
            `id_service` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_lang` int(10) unsigned NOT NULL ,
            `title` VARCHAR(300) NOT NULL,
            `desc` VARCHAR(300) NOT NULL,
            PRIMARY KEY (`id_service`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        return $return;
    }

    public function installSampleData()
    {
        $return = true;
        $servicesample = array(
            array(
                'title'=>'free shipping',
                'desc'=>'on order over $101',
                'image'=>'service-logo-1.png'
            ),
            array(
                'title'=>'24/7 support',
                'desc'=>'online support 24/7',
                'image'=>'service-logo-2.png'
            ),
            array(
                'title'=>'Secure Payments',
                'desc'=>'100% secure payment',
                'image'=>'service-logo-3.png'
            ),
            array(
                'title'=>'Money Back',
                'desc'=>'100% money Back',
                'image'=>'service-logo-4.png'
            ),
            array(
                'title'=>'Daily Discounts',
                'desc'=>'discount all items',
                'image'=>'service-logo-5.png'
            ),
        );

        $languages = Language::getLanguages(false);
        for ($i = 0; $i <count($servicesample); $i++) {
            $service = new RvServiceClass();
            $service->file_name = $servicesample[$i]['image'];
            $service->id_shop = $this->context->shop->id;
            foreach ($languages as $lang) {
                $service->title[$lang['id_lang']] = $servicesample[$i]['title'];
                $service->desc[$lang['id_lang']] = $servicesample[$i]['desc'];
            }
            $return &= $service->save();
        }
        return $return;
    }

    public function uninstall()
    {
        return $this->uninstallDB() &&
        parent::uninstall();
    }

    public function uninstallDB()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvservices`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvservices_lang`');
    }

    public function addToDB()
    {
        $filename = explode('.', $_FILES['file']['name']);
        return Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'rvservices` (`filename`,`title`,`desc`)
            VALUES ("'.((isset($filename[0]) && $filename[0] != '') ? pSQL($filename[0]) : '').
            '", "'.((Tools::getValue('title') != '') ? pSQL(Tools::getValue('title')) : '').'",
            "'.((Tools::getValue('desc') != '') ? pSQL(Tools::getValue('desc')) : '').'"
            )');
    }

    public function removeFromDB()
    {
        $dir = opendir(dirname(__FILE__).'/views/img');
        while (false !== ($file = readdir($dir))) {
            $path = dirname(__FILE__).'/views/img/'.$file;
            if ($file != '..' && $file != '.' && !is_dir($file)) {
                unlink($path);
            }
        }
        closedir($dir);

        return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'rvservices`');
    }

    public function getContent()
    {
        $html = '';
        $id_service = (int)Tools::getValue('id_service');

        if (Tools::isSubmit('savervservices')) {
            if ($id_service = Tools::getValue('id_service')) {
                $service = new RvServiceClass((int)$id_service);
            } else {
                $service = new RvServiceClass();
            }

            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $service->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
                $service->desc[$language['id_lang']] = Tools::getValue('desc_'.$language['id_lang']);
            }
            $service->id_shop = $this->context->shop->id;

            if ($service->validateFields(false) && $service->validateFieldsLang(false)) {
                $service->save();

                if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name'])) {
                    if ($error = ImageManager::validateUpload($_FILES['image'])) {
                        return false;
                    } elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['image']['tmp_name'], $tmpName)) {
                        return false;
                    } elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/views/img/service-'.(int)$service->id.'-'.(int)$service->id_shop.'.jpg')) {
                        return false;
                    }

                    unlink($tmpName);
                    $service->file_name = 'service-'.(int)$service->id.'-'.(int)$service->id_shop.'.jpg';
                    $service->save();
                }
                $this->_clearCache('*');
            } else {
                $html = $this->displayError($this->l('An error occurred while attempting to save.'));
            }
        }

        if (Tools::isSubmit('updatervservices') || Tools::isSubmit('addrvservices')) {
            $helper = $this->initForm();
            foreach (Language::getLanguages(false) as $lang) {
                if ($id_service) {
                    $service = new RvServiceClass((int)$id_service);
                    $helper->fields_value['title'][(int)$lang['id_lang']] = $service->title[(int)$lang['id_lang']];
                    $helper->fields_value['desc'][(int)$lang['id_lang']] = $service->desc[(int)$lang['id_lang']];
                    $image = dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$service->file_name;
                    $this->fields_form[0]['form']['input'][0]['image'] = '<img src="'.$this->getImageURL($service->file_name).'" />';
                } else {
                    $helper->fields_value['title'][(int)$lang['id_lang']] = Tools::getValue('title'.(int)$lang['id_lang'], '');
                    $helper->fields_value['desc'][(int)$lang['id_lang']] = Tools::getValue('desc_'.(int)$lang['id_lang'], '');
                }
            }
            if ($id_service = Tools::getValue('id_service')) {
                $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_service');
                $helper->fields_value['id_service'] = (int)$id_service;
            }

            return $html.$helper->generateForm($this->fields_form);
        } elseif (Tools::isSubmit('deletervservices')) {
            $service = new RvServiceClass((int)$id_service);
            if (file_exists(dirname(__FILE__).'/views/img/'.$service->file_name)) {
                unlink(dirname(__FILE__).'/views/img/'.$service->file_name);
            }
            $service->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } else {
            $content = $this->getListContent((int)Configuration::get('PS_LANG_DEFAULT'));
            $helper = $this->initList();
            $helper->listTotal = count($content);
            return $html.$helper->generateList($content, $this->fields_list);
        }

        if ($this->removeFromDB() && $this->addToDB()) {
            $this->_clearCache($this->templateFile);
            $html = $this->displayConfirmation($this->l('The block configuration has been updated.'));
        } else {
            $html = $this->displayError($this->l('An error occurred while attempting to save.'));
        }
    }

    protected function getListContent($id_lang)
    {
        return  Db::getInstance()->executeS('
            SELECT r.`id_service`, r.`id_shop`, r.`file_name`, rl.`title`, rl.`desc`
            FROM `'._DB_PREFIX_.'rvservices` r
            LEFT JOIN `'._DB_PREFIX_.'rvservices_lang` rl ON (r.`id_service` = rl.`id_service`)
            WHERE `id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang());
    }

    protected function initForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Service Configuration')
                ),
            'input' => array(
                array(
                    'type' => 'file',
                    'label' =>  $this->l('Service Logo'),
                    'name' => 'image',
                    'value' => true,
                    'display_image' => true,
                    ),
                array(
                    'type' => 'text',
                    'label' =>  $this->l('Service Title'),
                    'lang' => true,
                    'name' => 'title',
                    'cols' => 40,
                    'rows' => 10
                    ),
                array(
                    'type' => 'text',
                    'label' =>  $this->l('Service Description'),
                    'lang' => true,
                    'name' => 'desc',
                    'cols' => 40,
                    'rows' => 10
                    )
                ),
            'submit' => array(
                'title' => $this->l('Save'),
                )
            );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'rvservices';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'savervservices';
        $helper->toolbar_btn =  array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                )
            );
        return $helper;
    }

    protected function initList()
    {
        $this->fields_list = array(
            'id_service' => array(
                'title' => $this->l('ID'),
                'width' => 120,
                'type' => 'text',
                'search' => false,
                'orderby' => false
                ),
            'title' => array(
                'title' => $this->l('Title'),
                'width' => 140,
                'type' => 'text',
                'search' => false,
                'orderby' => false
                ),
            'desc' => array(
                'title' => $this->l('Description'),
                'width' => 140,
                'type' => 'text',
                'search' => false,
                'orderby' => false
                ),
            );

        if (Shop::isFeatureActive()) {
            $this->fields_list['id_shop'] = array(
                'title' => $this->l('ID Shop'),
                'align' => 'center',
                'width' => 25,
                'type' => 'int'
                );
        }

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_service';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] =  array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
            );

        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper;
    }

    protected function _clearCache($template, $cacheId = null, $compileId = null)
    {
        parent::_clearCache($this->templateFile);
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('rvservices'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('rvservices'));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $services = $this->getListContent($this->context->language->id);

        foreach ($services as &$service) {
            $service['image'] = $this->getImageURL($service['file_name']);
        }

        return array(
            'services' => $services,
            'IMGPATH' => _MODULE_DIR_ . $this->name.'/views/img/',
            );
    }

    private function getImageURL($image)
    {
        return $this->context->link->getMediaLink(__PS_BASE_URI__.'modules/'.$this->name.'/views/img/'.$image);
    }
}
