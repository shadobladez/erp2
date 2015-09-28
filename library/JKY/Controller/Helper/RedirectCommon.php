<?php
/**
 * Simple helper to encapsulate our most common redirects
 *
 * @category   iAds
 * @package    JKY_Controller_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class          JKY_Controller_Helper_RedirectCommon 
extends        Zend_Controller_Action_Helper_Abstract {

     protected $_redirector;

     public function __construct() {
          $this->_redirector = new Zend_Controller_Action_Helper_Redirector();
     }

     public function gotoLogin() {
          $this->_redirector->gotoRoute( array( 'controller' => 'customer', 'action' => 'login', ), 'default', true );
     }

     public function direct( $redirect ) {
          if(  method_exists( $this, $redirect )) {
               return $this->$redirect();
          }
          throw new JKY_Exception( 'Unknown common redirect method' );
     }
}