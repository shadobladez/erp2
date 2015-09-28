<?php
/**
 * Module service finder
 *
 * @category   iAds
 * @package    JKY_Controller_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class          JKY_Controller_Helper_Service 
extends        Zend_Controller_Action_Helper_Abstract {

     protected $_services = array();

     public function getService( $service, $module ) {
          if( !isset( $this->_services[ $module ][ $service ])) {
               $class = implode( '_', array( ucfirst( $module ), 'Service', ucfirst( $service )));
               $front = Zend_Controller_Front::getInstance();
               $classPath = $front->getModuleDirectory( $module ) . '/services/' . ucfirst( $service ) . '.php';
               if( !file_exists( $classPath )) {
                    return false;
               }
               if( !class_exists( $class )) {
                    throw new JKY_Exception( "Class $class not found in " . basename( $classPath ));
               }
               $this->_services[ $module ][ $service ] = new $class();
          }
          return $this->_services[ $module ][ $service ];
     }

     public function direct( $service, $module ) {
          return $this->getService( $service, $module );
     }
}