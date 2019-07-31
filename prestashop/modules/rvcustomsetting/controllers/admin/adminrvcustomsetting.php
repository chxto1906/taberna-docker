<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

class AdminrvcustomsettingController extends ModuleAdminController
{
    public function __construct()
    {
        // parent::initcontent();
        $module = "rvcustomsetting";
        Tools::redirectAdmin('index.php?controller=AdminModules&configure='.$module.
            '&token='.Tools::getAdminTokenLite('AdminModules'));
    }
}
