<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.adapter.caching.configuration' shared service.

return $this->services['prestashop.adapter.caching.configuration'] = new \PrestaShop\PrestaShop\Adapter\Cache\CachingConfiguration(${($_ = isset($this->services['prestashop.adapter.memcache_server.manager']) ? $this->services['prestashop.adapter.memcache_server.manager'] : $this->load('getPrestashop_Adapter_MemcacheServer_ManagerService.php')) && false ?: '_'}, ${($_ = isset($this->services['prestashop.adapter.php_parameters']) ? $this->services['prestashop.adapter.php_parameters'] : $this->services['prestashop.adapter.php_parameters'] = new \PrestaShop\PrestaShop\Adapter\Configuration\PhpParameters('/html/app/config/parameters.php')) && false ?: '_'}, ${($_ = isset($this->services['prestashop.adapter.cache_clearer']) ? $this->services['prestashop.adapter.cache_clearer'] : $this->services['prestashop.adapter.cache_clearer'] = new \PrestaShop\PrestaShop\Adapter\Cache\CacheClearer()) && false ?: '_'}, false, 'CacheMemcache');
