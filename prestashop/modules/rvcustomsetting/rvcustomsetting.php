<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once('classes/rvcustomsetting_image.class.php');

class RvCustomSetting extends Module
{
    public function __construct()
    {
        $this->name = 'rvcustomsetting';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('RV - Custom Setting');
        $this->description = $this->l('It is use of Custom Setting in RV Templates Theme');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = '';

        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted.'.
            ' Are you sure you want uninstall this module?');
    }


    public function install()
    {
        $this->installTab();
        $this->craeteVariable();
        Tools::clearSmartyCache();
        
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayDownloadApps') &&
            $this->registerHook('displayRightStickyOption') &&
            $this->registerHook('displayBackgroundBody');
    }

    public function installTab()
    {
        if (!(int)Tab::getIdFromClassName('AdminRVTemplates')) {
            $parent_tab = new Tab();
            // Need a foreach for the language
            foreach (Language::getLanguages() as $language) {
                $parent_tab->name[$language['id_lang']] = $this->l('RV Templates');
            }
            $parent_tab->class_name = 'AdminRVTemplates';
            $parent_tab->id_parent = 0; // Home tab
            $parent_tab->module = $this->name;
            $parent_tab->add();
        }
        $tab = new Tab();
        $tab->active = 1;
        foreach (Language::getLanguages() as $language) {
            $tab->name[$language['id_lang']] = $this->l('Custom Setting');
        }
        $tab->class_name = 'Admin'.$this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminRVTemplates');
        $tab->module = $this->name;
        $tab->add();
    }


    public function craeteVariable()
    {


        $result = array();
        $languages = Language::getLanguages();

        foreach ($languages as $lang) {
            $result['RVCUSTOMSETTING_APPS_TITLE'][$lang['id_lang']] = 'Download Our App';
            $result['RVCUSTOMSETTING_APPS_APPLE'][$lang['id_lang']] = '#';
            $result['RVCUSTOMSETTING_APPS_GOOLGE'][$lang['id_lang']] = '#';
            $result['RVCUSTOMSETTING_APPS_MICROSOFT'][$lang['id_lang']] = '#';

        }

        // App Links
        $tmp = $result['RVCUSTOMSETTING_APPS_TITLE'];
        Configuration::updateValue('RVCUSTOMSETTING_APPS_TITLE', $tmp);
        $tmp = $result['RVCUSTOMSETTING_APPS_APPLE'];
        Configuration::updateValue('RVCUSTOMSETTING_APPS_APPLE', $tmp);
        $tmp = $result['RVCUSTOMSETTING_APPS_GOOLGE'];
        Configuration::updateValue('RVCUSTOMSETTING_APPS_GOOLGE', $tmp);
        $tmp = $result['RVCUSTOMSETTING_APPS_MICROSOFT'];
        Configuration::updateValue('RVCUSTOMSETTING_APPS_MICROSOFT', $tmp);
        Configuration::updateValue('RVCUSTOMSETTING_APPS_STATUS', 1);

        // Rigt Sticky
        Configuration::updateValue('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS', 1);

        // Create Globle Variables
        Configuration::updateValue('RVCUSTOMSETTING_ADD_CONTAINER', 0);

        // Theme Option
        Configuration::updateValue('RVCUSTOMSETTING_THEME_OPTION', '', true);
        Configuration::updateValue('RVCUSTOMSETTING_THEME_COLOR_1', '#ffffff');
        Configuration::updateValue('RVCUSTOMSETTING_THEME_COLOR_2', '#ffffff');
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_COLOR', '#ffffff');
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_PATTERN', 'pattern1');
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN', 'no_pattern.png');
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS', 'pattern');
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_STYLE_SHEET', '');
    }

    public function uninstall()
    {
        Tools::clearSmartyCache();
        $this->uninstallTab();
        $this->deleteVariable();
        return parent::uninstall();
    }

    public function deleteVariable()
    {

        // App Links
        Configuration::deleteByName('RVCUSTOMSETTING_APPS_TITLE');
        Configuration::deleteByName('RVCUSTOMSETTING_APPS_APPLE');
        Configuration::deleteByName('RVCUSTOMSETTING_APPS_GOOLGE');
        Configuration::deleteByName('RVCUSTOMSETTING_APPS_MICROSOFT');
        Configuration::deleteByName('RVCUSTOMSETTING_APPS_STATUS');

        // Rigt Sticky Status
        Configuration::deleteByName('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS');

        // Create Globle Variables
        Configuration::deleteByName('RVCUSTOMSETTING_ADD_CONTAINER');

        // Theme Option
        Configuration::deleteByName('RVCUSTOMSETTING_THEME_OPTION');
        Configuration::deleteByName('RVCUSTOMSETTING_THEME_COLOR_1');
        Configuration::deleteByName('RVCUSTOMSETTING_THEME_COLOR_2');
        Configuration::deleteByName('RVCUSTOMSETTING_BACKGROUND_COLOR');
        Configuration::deleteByName('RVCUSTOMSETTING_BACKGROUND_PATTERN');
        Configuration::deleteByName('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
        Configuration::deleteByName('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
        Configuration::deleteByName('RVCUSTOMSETTING_BACKGROUND_STYLE_SHEET');
    }

    public function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Admin'.$this->name);
        $tab = new Tab($id_tab);
        $tab->delete();
        return true;
    }

    public function getContent()
    {
        $message = '';
        $this->context->smarty->assign('tab_number', '#fieldset_0');
        $message = $this->postProcess();
        $output = $message.'<div class="rvadmincustom-setting">'
            .$this->display(__FILE__, 'views/templates/admin/index.tpl').$this->renderForm().'</div>';
        return $output;
    }

    public function postProcess()
    {
        $message = '';
        $languages = Language::getLanguages();
        $result = array();

        if (Tools::isSubmit('submitRvThemeOptionForm')) {
            if ($_FILES['rvcustomsetting_custom_pattern']) {
                $this->obj_image = new RvCustomSettingImageUpload();
                $old_pattern = Configuration::get('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
                $ans = $this->obj_image->imageUploading($_FILES['rvcustomsetting_custom_pattern'], $old_pattern);
                if ($ans['success']) {
                    $file_name = $ans['name'];
                    Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN', $file_name);
                }
            }

            $tmp = Tools::getValue('RVCUSTOMSETTING_THEME_OPTION');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_THEME_OPTION')) {
                Configuration::updateValue('RVCUSTOMSETTING_THEME_OPTION', $tmp);
            }
            
            $tmp = Tools::getValue('RVCUSTOMSETTING_THEME_COLOR_1');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_THEME_COLOR_1')) {
                Configuration::updateValue('RVCUSTOMSETTING_THEME_COLOR_1', $tmp);
            }

            $tmp = Tools::getValue('RVCUSTOMSETTING_THEME_COLOR_2');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_THEME_COLOR_2')) {
                Configuration::updateValue('RVCUSTOMSETTING_THEME_COLOR_2', $tmp);
            }

            $tmp = Tools::getValue('RVCUSTOMSETTING_BACKGROUND_COLOR');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_BACKGROUND_COLOR')) {
                Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_COLOR', $tmp);
            }
            
            $tmp = Tools::getValue('rvcustomsetting_pattern');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_BACKGROUND_PATTERN')) {
                Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_PATTERN', $tmp);
            }
            
            $tmp = Tools::getValue('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS')) {
                Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS', $tmp);
            }

            $tmp = Tools::getValue('RVCUSTOMSETTING_ADD_CONTAINER');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')) {
                Configuration::updateValue('RVCUSTOMSETTING_ADD_CONTAINER', $tmp);
            }

            $tmp = Tools::getValue('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS')) {
                Configuration::updateValue('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS', $tmp);
            }
            
            $this->makeInslineStyleSheet();
            $this->context->smarty->assign('tab_number', '#fieldset_0');
            $message .= $this->displayConfirmation($this->l("Theme Configuration is Updates"));
        }


        if (Tools::isSubmit('submitRvAppLinkForm')) {
            foreach ($languages as $lang) {
                $tmp = Tools::getValue('RVCUSTOMSETTING_APPS_TITLE_'.$lang['id_lang']);
                $result['RVCUSTOMSETTING_APPS_TITLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('RVCUSTOMSETTING_APPS_APPLE_'.$lang['id_lang']);
                $result['RVCUSTOMSETTING_APPS_APPLE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('RVCUSTOMSETTING_APPS_GOOLGE_'.$lang['id_lang']);
                $result['RVCUSTOMSETTING_APPS_GOOLGE'][$lang['id_lang']] = $tmp;
                $tmp = Tools::getValue('RVCUSTOMSETTING_APPS_MICROSOFT_'.$lang['id_lang']);
                $result['RVCUSTOMSETTING_APPS_MICROSOFT'][$lang['id_lang']] = $tmp;
            }

            $tmp = $result['RVCUSTOMSETTING_APPS_TITLE'];
            Configuration::updateValue('RVCUSTOMSETTING_APPS_TITLE', $tmp);
            
            $tmp = $result['RVCUSTOMSETTING_APPS_APPLE'];
            Configuration::updateValue('RVCUSTOMSETTING_APPS_APPLE', $tmp);

            $tmp = $result['RVCUSTOMSETTING_APPS_GOOLGE'];
            Configuration::updateValue('RVCUSTOMSETTING_APPS_GOOLGE', $tmp);

            $tmp = $result['RVCUSTOMSETTING_APPS_MICROSOFT'];
            Configuration::updateValue('RVCUSTOMSETTING_APPS_MICROSOFT', $tmp);

            $tmp = Tools::getValue('RVCUSTOMSETTING_APPS_STATUS');
            if ($tmp != Configuration::get('RVCUSTOMSETTING_APPS_STATUS')) {
                Configuration::updateValue('RVCUSTOMSETTING_APPS_STATUS', $tmp);
            }

            $this->context->smarty->assign('tab_number', '#fieldset_1_1');
            $message .= $this->displayConfirmation($this->l("App Link is Updated"));
        }

        Tools::clearSmartyCache();
        return $message;
    }


    public function colorLuminance($hex, $percent)
    {
        $hex = preg_replace('/[^0-9a-f]/i', '', $hex);
        $new_hex = '#';
        
        if (Tools::strlen($hex) < 6) {
            $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }
        
        // convert to decimal and change luminosity
        for ($i = 0; $i < 3; $i++) {
            $dec = hexdec(Tools::substr($hex, $i*2, 2));
            $dec = min(max(0, $dec + $dec * $percent), 255);
            $new_hex .= str_pad(dechex($dec), 2, 0, STR_PAD_LEFT);
        }
        
        return $new_hex;
    }
    public function hexTorgb($hex){
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return "rgba($r,$g,$b,0.85)";
    }
    

    public function createCustomThemeCss(
        $filename,
        $newfilename,
        $string_to_replace1,
        $replace_with1,
        $string_to_replace2,
        $replace_with2,
        $string_to_replace3,
        $replace_with3,
        $string_to_replace4,
        $replace_with4,
        $color_replace_hexTorgb,
        $hexTorgb
    ) {
        $content= Tools::file_get_contents($filename);
        
        $content_chunks=explode($string_to_replace1, $content);
        $content=implode($replace_with1, $content_chunks);

        $content_chunks=explode($string_to_replace2, $content);
        $content=implode($replace_with2, $content_chunks);

        $content_chunks=explode($string_to_replace3, $content);
        $content=implode($replace_with3, $content_chunks);
        $content_chunks=explode($string_to_replace4, $content);
        $content=implode($replace_with4, $content_chunks);
         
        $content_chunks=explode($color_replace_hexTorgb, $content);
        $content=implode($hexTorgb, $content_chunks);        
                
        file_put_contents($newfilename, $content);
    }

    public function makeInslineStyleSheet()
    {
        $style = '';
        if (Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')) {
            if (Configuration::get('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS') == 'color') {
                $color = Configuration::get('RVCUSTOMSETTING_BACKGROUND_COLOR');
                $style = 'background-color:'.$color;
            } else {
                $img = '';
                if (Configuration::get('RVCUSTOMSETTING_BACKGROUND_PATTERN') == 'custompattern') {
                    $img = Configuration::get('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
                } else {
                    $img = Configuration::get('RVCUSTOMSETTING_BACKGROUND_PATTERN').'.png';
                }

                $path = _PS_BASE_URL_._MODULE_DIR_.$this->name."/views/img/".$img;
                $style = 'background-image:url('.$path.');background-attachment: fixed;';
            }
        }
        Configuration::updateValue('RVCUSTOMSETTING_BACKGROUND_STYLE_SHEET', $style);

        
    }

    public function hookDisplayBackgroundBody()
    {
        $this->makeInslineStyleSheet();
        return Configuration::get('RVCUSTOMSETTING_BACKGROUND_STYLE_SHEET');
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
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array(
            $this->rvThemeOptionForm(),
            $this->rvAppLinkForm()
        ));
    }

    protected function rvThemeOptionForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Theme Option'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 9,
                        'type' => 'custom_theme_option',
                        'name' => 'RVCUSTOMSETTING_THEME_OPTION',
                        'label' => $this->l('Theme Options'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => 'RVCUSTOMSETTING_THEME_COLOR_1',
                        'label' => $this->l('Custom Theme Color 1'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => 'RVCUSTOMSETTING_THEME_COLOR_2',
                        'label' => $this->l('Custom Theme Color 2'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Box Layout'),
                        'name' => 'RVCUSTOMSETTING_ADD_CONTAINER',
                        'desc' => $this->l('Box Layout Show in Front Side'),
                        'is_bool' => true,
                        'class' => 'rvadd-box',
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        ),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Background Theme'),
                        'name' => 'RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS',
                        'desc' => $this->l('Types of Background Styles'),
                        'is_bool' => true,
                        'class' => 'rvbackground-type',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 'color',
                                'label' => $this->l('Color')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 'pattern',
                                'label' => $this->l('Pattern')
                            )
                        ),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'label' => $this->l('Back Ground Theme Color'),
                        'name' => 'RVCUSTOMSETTING_BACKGROUND_COLOR',
                    ),
                    array(
                        'col' => 9,
                        'type' => 'file_upload_3',
                        'name' => 'RVCUSTOMSETTING_BACKGROUND_PATTERN',
                        'label' => $this->l('BackGround Pattern'),
                        'lang' => true,
                    ),
                    
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Right Side Sticky Status'),
                        'name' => 'RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS',
                        'desc' => $this->l('Display Right Side Sticky in Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitRvThemeOptionForm',
                ),
            ),
        );
    }

    // App Link Form
    protected function rvAppLinkForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('App Link'),
                'icon' => 'icon-cloud-upload',
                ),
                'input' => array(
                    array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'RVCUSTOMSETTING_APPS_TITLE',
                        'label' => $this->l('App Link Title'),
                        'lang' => true,
                        'desc' => $this->l('Display Title of All App Link in Front Side'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'RVCUSTOMSETTING_APPS_APPLE',
                        'label' => $this->l('Apple App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Apple App in Front Side'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'RVCUSTOMSETTING_APPS_GOOLGE',
                        'label' => $this->l('Google App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Google Link in Front Side'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'text',
                        'name' => 'RVCUSTOMSETTING_APPS_MICROSOFT',
                        'label' => $this->l('Microsoft App Link'),
                        'lang' => true,
                        'desc' => $this->l('Display Microsoft Link in Front Side'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'RVCUSTOMSETTING_APPS_STATUS',
                        'desc' => $this->l('Status of App Link in Front Side'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Show')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Hide')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitRvAppLinkForm',
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $fields = array();
        $languages = Language::getLanguages();
        $path = _PS_BASE_URL_._MODULE_DIR_.$this->name."/views/img/";

        foreach ($languages as $lang) {
            $a = Configuration::get('RVCUSTOMSETTING_APPS_TITLE', $lang['id_lang']);
            $fields['RVCUSTOMSETTING_APPS_TITLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('RVCUSTOMSETTING_APPS_APPLE', $lang['id_lang']);
            $fields['RVCUSTOMSETTING_APPS_APPLE'][$lang['id_lang']] = $a;

            $a = Configuration::get('RVCUSTOMSETTING_APPS_GOOLGE', $lang['id_lang']);
            $fields['RVCUSTOMSETTING_APPS_GOOLGE'][$lang['id_lang']] = $a;

            $a = Configuration::get('RVCUSTOMSETTING_APPS_MICROSOFT', $lang['id_lang']);
            $fields['RVCUSTOMSETTING_APPS_MICROSOFT'][$lang['id_lang']] = $a;
        }

        $tmp = Configuration::get('RVCUSTOMSETTING_APPS_STATUS');
        $fields['RVCUSTOMSETTING_APPS_STATUS'] = $tmp;
        
        $tmp = Configuration::get('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS');
        $fields['RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS'] = $tmp;
        
        $tmp = Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER');
        $fields['RVCUSTOMSETTING_ADD_CONTAINER'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_THEME_OPTION');
        $fields['RVCUSTOMSETTING_THEME_OPTION'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_THEME_COLOR_1');
        $fields['RVCUSTOMSETTING_THEME_COLOR_1'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_THEME_COLOR_2');
        $fields['RVCUSTOMSETTING_THEME_COLOR_2'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_BACKGROUND_COLOR');
        $fields['RVCUSTOMSETTING_BACKGROUND_COLOR'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_BACKGROUND_PATTERN');
        $fields['RVCUSTOMSETTING_BACKGROUND_PATTERN'] = $tmp;
        $this->context->smarty->assign('background_pattern', $tmp);

        $tmp = Configuration::get('RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS');
        $fields['RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS'] = $tmp;

        $tmp = Configuration::get('RVCUSTOMSETTING_BACKGROUND_OLD_PATTERN');
        
        $this->context->smarty->assign('custom_pattern', $tmp);
        $this->context->smarty->assign("path", $path);

        return $fields;
    }

    public function hookDisplayBackOfficeHeader()
    {
    	$this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }


    public function hookDisplayDownloadApps()
    {
        $html = '';
        $cookie = Context::getContext()->cookie;
        $id_lang = $cookie->id_lang;

        if (Configuration::get('RVCUSTOMSETTING_APPS_STATUS')) {
            $path = _PS_BASE_URL_SSL_._MODULE_DIR_.$this->name."/views/img/";
            $link_title = Configuration::get('RVCUSTOMSETTING_APPS_TITLE', $id_lang);
            $apple_link = Configuration::get('RVCUSTOMSETTING_APPS_APPLE', $id_lang);
            $google_link = Configuration::get('RVCUSTOMSETTING_APPS_GOOLGE', $id_lang);
            $microsoft_link = Configuration::get('RVCUSTOMSETTING_APPS_MICROSOFT', $id_lang);
            $html = '
                <div class=\'rvapp-block col-lg-12 rv-animate-element bottom-to-top\'>
                    <h4 class=\'title_block\'>'.$link_title.'</h4>

                    <ul class=\'toggle-footer\'>

                        <li>
                            <a href=\''.$apple_link.'\'>
                                <img src=\''.$path.'App_1.png\'>
                            </a>
                        </li>

                        <li>
                            <a href=\''.$google_link.'\'>
                                <img src=\''.$path.'App_2.png\'>
                            </a>
                        </li>

                        <li>
                            <a href=\''.$microsoft_link.'\'>
                                <img src=\''.$path.'App_3.png\'>
                            </a>
                        </li>

                    </ul>    
                </div>
            ';

            $apple_link;
            $google_link;
            $microsoft_link;

            return $html;
        }
        return false;
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css');

        $default_brand_primary = "#232f3e";
        $default_brand_secondary = "#ffd05f";

        $custom_brand_primary = Configuration::get('RVCUSTOMSETTING_THEME_COLOR_1');
        $custom_brand_secondary = Configuration::get('RVCUSTOMSETTING_THEME_COLOR_2');
     
		if (Configuration::get('RVCUSTOMSETTING_THEME_OPTION') == 'theme_custom') {
           $brand_primary = $custom_brand_primary;
           $brand_secondary = $custom_brand_secondary;
        }
        else {
        	$brand_primary = $default_brand_primary;
       		$brand_secondary = $default_brand_secondary;
        }


        Configuration::updateValue('RVFRONTSIDE_THEME_SETTING_SHOW', '0');
        $tmp = Configuration::get('RVFRONTSIDE_THEME_SETTING_SHOW');
        
        Media::addJsDef(
        	array(
        		'RVFRONTSIDE_THEME_SETTING_SHOW' => $tmp,
        		'rv_brand_primary' => $brand_primary,
                'rv_brand_secondary' => $brand_secondary,
                'rv_default_brand_primary' => $default_brand_primary,
                'rv_default_brand_secondary' => $default_brand_secondary,
                'rv_custom_brand_primary' => $custom_brand_primary,
                'rv_custom_brand_secondary' => $custom_brand_secondary,
        	)
        );

    }
}
