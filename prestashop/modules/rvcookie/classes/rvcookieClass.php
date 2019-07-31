<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 Copyright (c) permanent, RV Templates
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL.
*  @version   1.0.0
*
* NOTICE OF LICENSE
*
* Don't use this module on several shops. The license provided by PrestaShop Addons
* for all its modules is valid only once for a single shop.
*/

class rvcookieClass extends ObjectModel
{
	public $id;
	public $id_shop;
	public $text;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'rvcookie',
		'primary' => 'id_rvcookie',
		'multilang' => true,
		'fields' => array(
			'id_shop' =>			array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
			// Lang fields
			'text' =>				array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
		)
	);

}
