<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.core.helper_doc.meta_page_link_provider' shared service.

return $this->services['prestashop.core.helper_doc.meta_page_link_provider'] = new \PrestaShop\PrestaShop\Core\HelperDoc\MetaPageHelperDocLinkProvider(${($_ = isset($this->services['prestashop.adapter.legacy.context']) ? $this->services['prestashop.adapter.legacy.context'] : $this->services['prestashop.adapter.legacy.context'] = new \PrestaShop\PrestaShop\Adapter\LegacyContext()) && false ?: '_'}->getContext()->language->iso_code);
