<?
/**
 * Storefront_Catalog
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class          JKY_Model_Categories
extends        JKY_Model_Application{

     public function init() {
          parent::init( 'Categories', 'category_id' );
     }
     
}