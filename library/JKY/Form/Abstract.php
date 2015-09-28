<?php
/**
 * Simple base form class to provide model injection
 *
 * @category   iAds
 * @package    JKY_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class          JKY_Form_Abstract 
extends        Zend_Form {

     /**
      * @var JKY_Model_Interface
      */
     protected $_model;

     /**
      * Model setter
      * 
      * @param JKY_Model_Interface $model 
      */
     public function setModel( JKY_Model_Interface $model ) {
          $this->_model = $model;
     }

     /**
      * Model Getter
      * 
      * @return JKY_Model_Interface 
      */
     public function getModel() {
          return $this->_model;
     }
}