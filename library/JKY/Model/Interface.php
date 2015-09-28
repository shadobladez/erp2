<?php
/**
 * JKY_Model_Interface
 * 
 * All models use this interface
 * 
 * @category   iAds
 * @package    JKY_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface JKY_Model_Interface {

     public function __construct( $options = null );
     public function init       ();

     public function getResource( $name );
     public function getForm    ( $name );
}