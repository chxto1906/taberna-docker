<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.core.grid.query.webservice_key_query_builder' shared service.

return $this->services['prestashop.core.grid.query.webservice_key_query_builder'] = new \PrestaShop\PrestaShop\Core\Grid\Query\WebserviceKeyQueryBuilder(${($_ = isset($this->services['doctrine.dbal.default_connection']) ? $this->services['doctrine.dbal.default_connection'] : $this->getDoctrine_Dbal_DefaultConnectionService()) && false ?: '_'}, 'ps_', ${($_ = isset($this->services['prestashop.core.query.doctrine_search_criteria_applicator']) ? $this->services['prestashop.core.query.doctrine_search_criteria_applicator'] : $this->services['prestashop.core.query.doctrine_search_criteria_applicator'] = new \PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicator()) && false ?: '_'});
