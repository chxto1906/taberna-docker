<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.adapter.maintenance.form_handler' shared service.

return $this->services['prestashop.adapter.maintenance.form_handler'] = new \PrestaShop\PrestaShop\Core\Form\FormHandler(${($_ = isset($this->services['form.factory']) ? $this->services['form.factory'] : $this->load('getForm_FactoryService.php')) && false ?: '_'}->createBuilder(), ${($_ = isset($this->services['prestashop.core.hook.dispatcher']) ? $this->services['prestashop.core.hook.dispatcher'] : $this->getPrestashop_Core_Hook_DispatcherService()) && false ?: '_'}, ${($_ = isset($this->services['prestashop.adapter.maintenance.form_provider']) ? $this->services['prestashop.adapter.maintenance.form_provider'] : $this->load('getPrestashop_Adapter_Maintenance_FormProviderService.php')) && false ?: '_'}, array('general' => 'PrestaShopBundle\\Form\\Admin\\Configure\\ShopParameters\\General\\MaintenanceType'), 'MaintenancePage');
