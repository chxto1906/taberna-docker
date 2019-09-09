<?php
/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

class RvPopupEmailCore extends Module
{
    public $_html = '';

    public function getThemeFields()
    {
        $init_template = array(
            'general' => array(
                'title' => $this->l('Email Subscription Setting'),
                'input' => array(
                    'display_emailsubscription' => array(
                        'name' => 'Display_Emailsubscription',
                        'value' => 1,
                        'label' => $this->l('Display Email Subscription :'),
                        'type' => 'switch',
                    ),
                    'banner_popup_emailsub' => array(
                        'name'  => 'Banner_Popup_Emailsub',
                        'label' => $this->l('Image Email Subscription : '),
                        'value' => 'banner_email.jpg',
                        'type'  => 'file_image',
                    ),
                   'title_popup_emailsub' => array(
                        'name' => 'Title_Popup_Emailsub',
                        'label' => $this->l('Title Email Subscription :'),
                        'type' => 'textarea',
                        'value' => 'Extra Discounts!',
                    ),
                    'description_popup_emailsub' => array(
                        'name' => 'Description_Popup_Emailsub',
                        'label' => $this->l('Description Email Subscription :'),
                        'type' => 'textarea',
                        'value' => 'First time subcribers enjoy 5% off their first order.',
                    ),
                ),
            ),
        );
        return $init_template;
    }

    public function install()
    {
        return (parent::install() && $this->installFixtures());
    }

    protected function installFixtures()
    {
        $success = true;
        $languages = Language::getLanguages(false);
        $init = $this->getThemeFields();

        foreach ($init as $fiels) {
            foreach ($fiels['input'] as $item) {
                $valuefiels = (isset($item['value']))?$item['value']:'';
                if (isset($item['lang']) && $item['lang']) {
                    $valueByLang = array();
                    /* check exit value */
                    foreach ($languages as $lang)
                        $valueByLang[$lang['id_lang']] = $valuefiels;
                    $success &= Configuration::updateValue($item['name'], $valueByLang, true);
                } else {
                    $success &= Configuration::updateValue($item['name'], $valuefiels);
                }
            }
        }
        return $success;
    }

    public function uninstall()
    {
        $init = $this->getThemeFields();
        foreach ($init as $fields) {
            foreach ($fields['input'] as $item) {
                Configuration::deleteByName($item['name']);
            }
        }
        $this->deleteImage();
        return parent::uninstall();
    }

    public function deleteImage()
    {
        #category
        $save_path = _PS_MODULE_DIR_ . $this->name . '/img/';
        #folder cache
        $images = glob($save_path . '*.*');
        foreach ($images as $image) {
            $pos = strpos(basename($image), 'demo');
            if ($pos === false)
                unlink($save_path . basename($image));
        }
    }

    protected function getFormSection($fields_form, $title, $icon = 'icon-cogs')
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $title,
                    'icon' => $icon
                ),
                'input' => $fields_form,
                'buttons' => array(
                    'newBlock' => array(
                        'title' => $this->trans('Reset Config ', array(), 'Modules.Linklist.Admin'),
                         'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules').'&amp;restconfig',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-reset'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            )
        );
    }

    public function postProcess()
    {
        $this->_html = '';

        if (Tools::isSubmit('submitStoreConf')) {
            $languages = Language::getLanguages(false);
            $values = array();
            $init = $this->getThemeFields();
            foreach ($init as $fields) {
                foreach ($fields['input'] as $nameItem => $item) {
                    if (isset($item['lang']) && $item['lang']) {
                        foreach ($languages as $lang) {
                            $field_lang = $nameItem . '_' . $lang['id_lang'];
                            if ($item['type'] == 'file_lang') {
                                if (isset($_FILES[$field_lang])
                                    && isset($_FILES[$field_lang]['tmp_name'])
                                    && !empty($_FILES[$field_lang]['tmp_name'])
                                ) {
                                    if ($error = ImageManager::validateUpload($_FILES[$field_lang], 4000000)) {
                                        return $error;
                                    } else {
                                        $ext = substr($_FILES[$field_lang]['name'], strrpos($_FILES[$field_lang]['name'], '.') + 1);
                                        $file_name = md5($_FILES[$field_lang]['name']) . '.' . $ext;

                                        if (!move_uploaded_file($_FILES[$field_lang]['tmp_name'], _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR .$this->name.DIRECTORY_SEPARATOR.'img' . DIRECTORY_SEPARATOR . $file_name)) {
                                            return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                                        } else {
                                            $values[$nameItem][$lang['id_lang']] = $file_name;
                                        }
                                    }
                                }
                            } else {
                                $values[$nameItem][$lang['id_lang']] = Tools::getValue($field_lang);
                            }
                        }
                        Configuration::updateValue($item['name'], $values[$nameItem], true);
                    }else{
                        if($item['type'] == 'file_image') {
                            $file_name = '';
                            if (isset($_FILES[$nameItem])
                                && isset($_FILES[$nameItem]['tmp_name'])
                                && !empty($_FILES[$nameItem]['tmp_name'])
                            ){
                                if ($error = ImageManager::validateUpload($_FILES[$nameItem], 4000000)) {
                                    return $error;
                                } else {
                                    $ext = substr($_FILES[$nameItem]['name'], strrpos($_FILES[$nameItem]['name'], '.') + 1);
                                    $file_name = md5($_FILES[$nameItem]['name']) . '.' . $ext;

                                    if (!move_uploaded_file($_FILES[$nameItem]['tmp_name'], _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR .$this->name.DIRECTORY_SEPARATOR.'img' . DIRECTORY_SEPARATOR . $file_name)) {
                                        return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                                    }
                                }
                            }
                            if(!empty($file_name))
                                Configuration::updateValue($item['name'], $file_name);
                        }else{
                            Configuration::updateValue($item['name'], Tools::getValue($nameItem), true);
                        }
                    }
                }
            }

            $this->_html = $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }
        elseif (Tools::isSubmit('restconfig')){
            if($this->resetfield('fonts')){
                $this->_html = $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
            }else{
                $this->_html = $this->displayError($this->l('Error : Can Not Reset Please try again !.'));
            }
        }

    }
    public function resetfield($nameField){
        $init = $this->getThemeFields();
        $languages = Language::getLanguages(false);
        $success = true;
        if(array_key_exists($nameField,$init)){
            foreach ($init[$nameField]['input'] as $item){
                if (isset($item['lang']) && $item['lang']) {
                    $valueByLang = array();
                    foreach ($languages as $lang)
                        $valueByLang[$lang['id_lang']] = $item['value'];
                    $success &= Configuration::updateValue($item['name'], $valueByLang, true);
                } else {
                    $success &= Configuration::updateValue($item['name'], $item['value']);
                }
            }
        }
        return $success;
     }

    public function getContent()
    {
        // Load css file for option panel
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/admin/admin.css');

        return $this->postProcess() . $this->_html . $this->renderForm();
    }

    public function _initForm()
    {
        $init = $this->getThemeFields();
        $arrfields = array();
        foreach ($init as $keyfields => $fields) {
            $array_input = array();
            foreach ($fields['input'] as $key_item => $item) {
                $array_input[] = $this->processItemForm($item, $key_item);
            }
            $arrfields[$keyfields] = $this->getFormSection($array_input, $fields['title']);
        }


        return $arrfields;
    }

    public function processItemForm($item, $name)
    {
        $arrcontent = array();
        $arrcontent['name'] = $name;
        $arrcontent['label'] = $item['label'];
        $arrcontent['type'] = $item['type'];

        if (isset($item['lang']))
            $arrcontent['lang'] = $item['lang'];

        if (isset($item['desc']))
            $arrcontent['desc'] = $item['desc'];

        if (isset($item['cols']))
            $arrcontent['cols'] = $item['cols'];

        if (isset($item['rows']))
            $arrcontent['rows'] = $item['rows'];

        if (isset($item['class']))
            $arrcontent['class'] = $item['class'];

        if (isset($item['autoload_rte']))
            $arrcontent['autoload_rte'] = $item['autoload_rte'];

        if (isset($item['values']))
            $arrcontent['values'] = $item['values'];

        if ($item['type'] == 'select')
            $arrcontent['options'] = $item['options'];
        elseif ($item['type'] == 'switch')
            $arrcontent['values'] = array(
                array(
                    'id' => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                ),
                array(
                    'id' => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                )
            );
        return $arrcontent;

    }

    public function renderForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->submit_action = 'submitStoreConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        // Language
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'rvtabs' => $this->getRvTabs(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm($this->_initForm());
    }


    /*Get Tab to Display in cpanel in back end */
    public function getRvTabs()
    {
        $init = $this->getThemeFields();
        foreach ($init as $keyfields => $fields) {
            $fields['title'] = 'fieldset_' . $keyfields;
        }
    }

    /*init value for form */
    public function getConfigFieldsValues()
    {
        $fields_values = array();
        $init = $this->getThemeFields();
        foreach ($init as $configfield) {
            foreach ($configfield['input'] as $key => $item) {
                if (isset($item['lang']) && $item['lang']) {
                    $fields_values[$key] = Tools::getValue($key, Configuration::getInt($item['name']));
                } else {
                    $fields_values[$key] = Tools::getValue($key, Configuration::get($item['name']));
                }
            }
        }
        return $fields_values;
    }
}