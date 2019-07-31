<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class SincronizacionWebServices extends Module {

    public function __construct() {
        $this->name = 'sincronizacionwebservices';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'William Tapia I. ';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Sincronizacion de Productos');
        $this->description = $this->l('Sincornizacion de Productos a trav�s de  Web Services');
        $this->confirmUninstall = $this->l('Esta seguro que desea desinstalar este módulo?');

        if (!Configuration::get('sincronizacionwebservices'))
            $this->warning = $this->l('No name provided.');
    }

    public function install() {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
                !$this->registerHook('leftColumn') ||
                !$this->registerHook('header') ||
                !Configuration::updateValue('sincronizacionwebservices', 'Sincronizacion Productos')
        )
            return false;
        $this->initSQLConfiguration();
        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() ||
                !Configuration::deleteByName('sincronizacionwebservices')
        )
            return false;

        return true;
    }

    protected function initSQLConfiguration() {
        Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_ . $this->name) . '_asociacion_categorias` (
            `id` int(10) NOT NULL AUTO_INCREMENT,
            `categoria_sistema_remota` VARCHAR( 50 ) NOT NULL,  
            `categoria_sitio_web` VARCHAR( 50 ) NOT NULL,  
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ');
        return true;
    }

    
}
