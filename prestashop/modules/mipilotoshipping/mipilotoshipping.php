<?php
/**
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_ . 'mipilotoshipping/curl/curl_mipiloto.php';
require_once _PS_ROOT_DIR_ . '/config/taberna_config_facturacion.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mipilotoshipping extends CarrierModule
{
    protected $config_form = false;
    

    public function __construct()
    {
        $this->name = 'mipilotoshipping';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'Henry Campoverde';
        $this->need_instance = 1;
        $this->array_cities = array('cuenca' => 1, 'guayaquil' => 2, 'quito' => 3);
        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Mi Piloto Shipping');
        $this->description = $this->l('Mi Piloto Shipping para envío y cálculo de costo de envío');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $carrier = $this->addCarrier();
        $this->addZones($carrier);
        $this->addGroups($carrier);
        $this->addRanges($carrier);
        //Configuration::updateValue('MIPILOTOSHIPPING_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('updateCarrier') &&
            $this->registerHook('actionCarrierProcess') &&
            $this->registerHook('displayAdminOrderContentShip');
    }

    public function uninstall()
    {
        //Configuration::deleteByName('MIPILOTOSHIPPING_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMipilotoshippingModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMipilotoshippingModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    /*array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'MIPILOTOSHIPPING_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),*/
                    array(
                        //'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('Enter a valid Key'),
                        'name' => 'MIPILOTOSHIPPING_ACCOUNT_API_KEY',
                        'label' => $this->l('Api Key'),
                    )/*,
                    array(
                        'type' => 'password',
                        'name' => 'MIPILOTOSHIPPING_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),*/
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            //'MIPILOTOSHIPPING_LIVE_MODE' => Configuration::get('MIPILOTOSHIPPING_LIVE_MODE', true),
            //'MIPILOTOSHIPPING_ACCOUNT_EMAIL' => Configuration::get('MIPILOTOSHIPPING_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            //'MIPILOTOSHIPPING_ACCOUNT_PASSWORD' => Configuration::get('MIPILOTOSHIPPING_ACCOUNT_PASSWORD', null),
            'MIPILOTOSHIPPING_ACCOUNT_API_KEY' => Configuration::get('MIPILOTOSHIPPING_ACCOUNT_API_KEY', null)
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    private function getTipoVehiculo($quantity) {
        $tipo = 1;
        if ($quantity > LIMITE_CANTIDAD_PRODUCTOS_TIPO_VEHICULO) {
            $tipo = 2;
        }
        return $tipo;
    }


    private function findTarifa($data, $tipo_vehiculo) {
        $item = null;
        foreach($data as $struct) {
            if ($tipo_vehiculo == $struct->tipoVehiculoId) {
                $item = $struct;
                break;
            }
        }
        return $item;
    }

    private function getCityDeliveryByCoordenates($addr) {
        $resulta = false;
        $infoCiudad = $this->getCiudad($addr->latitude,$addr->longitude);
        if ($infoCiudad["status"] == 1){
            $result = json_decode($infoCiudad["result"]);
            $ciudad = trim(strtolower($result->results[0]->components->city));
            $ciudad_shop = $this->get_city_by_id(Context::getContext()->shop->id);
            if ($ciudad == $ciudad_shop){
                $resulta = true;
            }
        }
        return $resulta;
    }

    private function get_city_by_id(){
        $id = Context::getContext()->shop->id;
        switch ((int)$id) {
            case 3: return "cuenca";
            case 4: return "cuenca";
            case 5: return "cuenca";
            case 17: return "cuenca";
            case 18: return "cuenca";
            case 9: return "guayaquil";
            case 10: return "guayaquil";
            case 11: return "guayaquil";
            case 12: return "quito";
            case 6: return "quito";
            case 7: return "quito";
            case 19: return "quito";
            case 20: return "quito";
            case 21: return "quito";
            case 23: return "quito";
            case 1: return "manta";
            case 24: return "manta";
            case 14: return "loja";
            case 15: return "machala";
            case 16: return "ambato";
            case 22: return "santo domingo";
            case 25: return "general villamil";
            default: return null; 
        }
    }


    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
      }
      else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
      }
    }


    /******** AGREGADO POR HENRY *********/
    public function getCiudad($lat,$lng) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.opencagedata.com/geocode/v1/json?q=".$lat."+".$lng."&key=d787ef2345f841429efd7f5f6249d4e7",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return array("status" => 0, "result" => "cURL Error #:" . $err);
        } else {
          return array("status" => 1, "result" => $response);
        }
    }


    public function getOrderShippingCost($params, $shipping_cost)
    {
        if (Context::getContext()->customer->logged == true)
        {
            $cart = Context::getContext()->cart;
            $qty = Cart::getNbProducts($cart->id);
            $tipo_vehiculo = $this->getTipoVehiculo($qty);
            if ($cart->id_address_delivery) {
                $id_address_delivery = $cart->id_address_delivery;
                $address = new Address($id_address_delivery);
                // Guardar el valor que se debería cobrar en el cart
                $validate = $this->getCityDeliveryByCoordenates($address);
                if ($validate){
                    $curlmipiloto = new CurlMiPiloto();
                    $bodyCotizar = $this->generateBodyCotizar($address);
                    $resultCotizar = $curlmipiloto->cotizar($bodyCotizar);
                    if ($resultCotizar["status"] == 1) {
                        if (!empty($resultCotizar["result"])) {
                            $result = $resultCotizar["result"];
                            $result = json_decode($result);
                            if (isset($result->tarifa)) {
                                
                                $tarifa = $this->findTarifa($result->tarifa, $tipo_vehiculo);
                                if (isset($tarifa->valor)) {
                                    $shipping_cost = $tarifa->valor;
                                }else{
                                    $tarifa = $this->findTarifa($result->tarifa, 1);
                                    if (isset($tarifa->valor)) {
                                        $shipping_cost = $tarifa->valor;
                                    }
                                }
                                $isPromo = $this->isPromo($bodyCotizar,$cart);
                                if ($isPromo) {
                                    $this->saveBitacoraValor($shipping_cost,$cart);
                                    $shipping_cost = 0;
                                }else{
                                    $this->saveBitacoraValor(0,$cart);
                                }
                            }
                        }
                    }
                } 
            }
        }
        return $shipping_cost;
    }

    private function saveBitacoraValor($valor,$cart) {
        $cart->shipping_promo_tax_exc = $valor;
        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);
        $IVA_VALUE = $config["IVA_VALUE"];
        $cart->shipping_promo_tax_inc = (($valor*$IVA_VALUE)/100)+$valor;
        $guardado = $cart->save();
    }


    private function isPromo($bodyCotizar,$cart) {
        $result = false;
        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);
        if ($config["PROMO_TRANSPORTE_GRATIS"] == true) {
            $PROMO_TRANSPORTE_KM_MIN = $config["PROMO_TRANSPORTE_KM_MIN"];
            $PROMO_TRANSPORTE_CONSUMO_MINIMO = $config["PROMO_TRANSPORTE_CONSUMO_MINIMO"];
            $total = $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
            $latitud = $bodyCotizar["latitud"];
            $longitud = $bodyCotizar["longitud"];
            $latitudLlegada = $bodyCotizar["latitudLlegada"];
            $longitudLlegada = $bodyCotizar["longitudLlegada"];
            $distance = $this->distance($latitud,$longitud,$latitudLlegada,$longitudLlegada,"K");
            if (($distance <= $PROMO_TRANSPORTE_KM_MIN) && ($total >= $PROMO_TRANSPORTE_CONSUMO_MINIMO))
                $result = true;
        }
        return $result;
    }

    public function getOrderDeliveryTime($delivery_time)
    {
        if (Context::getContext()->customer->logged == true)
        {
            $cart = Context::getContext()->cart;
            if ($cart->id_address_delivery){
                $id_address_delivery = $cart->id_address_delivery;
                $address = new Address($id_address_delivery);
                $bodyCotizar = $this->generateBodyCotizar($address);
                $curlmipiloto = new CurlMiPiloto();
                $resultCotizar = $curlmipiloto->cotizar($bodyCotizar);
                if ($resultCotizar["status"] == 1){
                    if (!empty($resultCotizar["result"])) {
                        $result = $resultCotizar["result"];
                        $result = json_decode($result);
                        //$valor = $result->tarifa[0]->valor;
                        //$valorNoche1 = $result->tarifa[0]->valorNoche1;
                        //$valorNoche2 = $result->tarifa[0]->valorNoche2;
                        $delivery_time = $result->tiempo_llegada;
                        //return $tiempo;
                    }    
                }
            }
        }

        //var_dump("SIIIII DEVOLVER");


        return $delivery_time;
    }

    public function infoPedido($num_guia) {
        $result = null;
        if ($num_guia){
            $curlmipiloto = new CurlMiPiloto();
            $resultInfoPedido = $curlmipiloto->infoPedido($num_guia);
            if (is_array($resultInfoPedido)) {
                if ($resultInfoPedido["status"] == 1){
                    $result = $resultInfoPedido["result"];
                }
            }
        }
        return $result;
    }


    public function activarPedido($guia_numero,$tipo_vehiculo,$tipo_producto,$tiempo_llegada){
        $result = null;
        $curlmipiloto = new CurlMiPiloto();
        $resultHacerPedido = $curlmipiloto->activarPedido($guia_numero,$tipo_vehiculo,$tipo_producto,$tiempo_llegada);
        if ($resultHacerPedido["status"] == 1){
            $result = $resultHacerPedido["result"];
            $result = json_decode($result);
        }
        return $result;
    }


    public function eliminarPedido($guia_numero){
        $result = null;
        $curlmipiloto = new CurlMiPiloto();
        $resultHacerPedido = $curlmipiloto->eliminarPedido($guia_numero);
        if ($resultHacerPedido["status"] == 1){
            $result = $resultHacerPedido["result"];
            $result = json_decode($result);
        }
        return $result;
    }


    public function agendarPedido($order,$efectivo){
        $result = null;


        $total = (float) $order->total_paid;

        /*if (Context::getContext()->customer->logged == true)   
        {*/
            $id_address_delivery = $order->id_address_delivery;
            $address = new Address($id_address_delivery);

            $resultGetCityStore = $this->getStoreCurrent($order);
            $cityStoreCurrent = $this->array_cities[strtolower(trim($resultGetCityStore[0]["city"]))];
            $cityStoreCurrent = empty($resultGetCityStore) 
                    ? 1 : $this->array_cities[strtolower(trim($resultGetCityStore[0]["city"]))];

            $latitudeAddressStore = empty($resultGetCityStore) ? 0 : $resultGetCityStore[0]["latitude"];
            $longitudeAddressStore = empty($resultGetCityStore) ? 0 : $resultGetCityStore[0]["longitude"];
            
            $latitudeAddressCustomer = $address->latitude;
            $longitudeAddressCustomer = $address->longitude;

            $curlmipiloto = new CurlMiPiloto();

            $shop = Context::getContext()->shop;
            $customer = Context::getContext()->customer;


            $body = array(
                "latitud_recibe" => $latitudeAddressStore,
                "longitud_recibe" => $longitudeAddressStore,
                "persona_recibe" => $shop->name,
                "telefono_recibe" => $resultGetCityStore[0]["phone"],
                "local_recibe" => $shop->name,
                "direccion_recibe" => $resultGetCityStore[0]["address1"],
                "referencia_recibe" => $resultGetCityStore[0]["address1"],
                "latitud_entrega" => $latitudeAddressCustomer,
                "longitud_entrega" => $longitudeAddressCustomer,
                "persona_entrega" => $address->lastname." ".$address->firstname,
                "telefono_entrega" => $address->phone,
                "direccion_entrega" => $address->address1,
                "referencia_entrega" => $address->address2,
                "cedula_entrega" => $address->dni,
                "tiempo_llegada" => "60",
                "email_usuario" => $address->email,
                "monto" => $total
            );

            

            $resultHacerPedido = $curlmipiloto->hacerPedido($body,$efectivo);

            if ($resultHacerPedido["status"] == 1){
                $result = $resultHacerPedido["result"];
                $result = json_decode($result);
            }
        /*}*/
        return $result;

    }


    public function isNight(){
        $night = false;
        $time = date("H");
        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");
        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "18") {
            $night = true;
        }
        return $night;
    }



    public function generateBodyCotizar($address){
        $resultGetCityStore = $this->getStoreCurrent();
        $cityStoreCurrent = $this->array_cities[strtolower(trim($resultGetCityStore[0]["city"]))];
        $cityStoreCurrent = empty($resultGetCityStore) 
                ? 1 : $this->array_cities[strtolower(trim($resultGetCityStore[0]["city"]))];

        $latitudeAddressStore = empty($resultGetCityStore) ? 0 : $resultGetCityStore[0]["latitude"];
        $longitudeAddressStore = empty($resultGetCityStore) ? 0 : $resultGetCityStore[0]["longitude"];
        
        $latitudeAddressCustomer = $address->latitude;
        $longitudeAddressCustomer = $address->longitude;

        return array('latitud' => $latitudeAddressStore,'longitud' => $longitudeAddressStore,
            'latitudLlegada' => $latitudeAddressCustomer,'longitudLlegada' => $longitudeAddressCustomer,'ciudad' => $cityStoreCurrent);
    }


    public function getStoreCurrent($order=null){
        if (!$order)
            $id_shop_current = (int)Context::getContext()->shop->id;
        else
            $id_shop_current = (int)$order->id_shop;
        return  Db::getInstance()->executeS('
         SELECT *
         FROM `'._DB_PREFIX_.'store_shop` as stsh
         LEFT JOIN `'._DB_PREFIX_.'store` as st
         ON st.`id_store` = stsh.`id_store`
         LEFT JOIN `'._DB_PREFIX_.'store_lang` as stl
         ON st.`id_store` = stl.`id_store`
         WHERE stl.id_lang = 1 AND st.`active` = 1 AND stsh.`id_shop` ='.$id_shop_current);
    }


    public function getOrderShippingCostExternal($params)
    {
        return true;
    }

    protected function addCarrier()
    {
        $carrier = new Carrier();

        $carrier->name = $this->l('My super carrier');
        $carrier->is_module = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->name;
        $carrier->shipping_method = 2;

        foreach (Language::getLanguages() as $lang)
            $carrier->delay[$lang['id_lang']] = $this->l('Super fast delivery');

        if ($carrier->add() == true)
        {
            @copy(dirname(__FILE__).'/views/img/carrier_image.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg');
            Configuration::updateValue('MYSHIPPINGMODULE_CARRIER_ID', (int)$carrier->id);
            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group)
            $groups_ids[] = $group['id_group'];

        $carrier->setGroups($groups_ids);
    }

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone)
            $carrier->addZone($zone['id_zone']);
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookUpdateCarrier($params)
    {
        /**
         * Not needed since 1.5
         * You can identify the carrier by the id_reference
        */
    }

    public function hookActionCarrierProcess()
    {
        /* Place your code here. */
    }

    public function hookDisplayAdminOrderContentShip()
    {
        /* Place your code here. */
    }
}
