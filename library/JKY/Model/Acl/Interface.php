<?php
/**
 * JKY_Model_Acl_Interface
 *
 * @category   iAds
 * @package    JKY_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface JKY_Model_Acl_Interface {

     public function setIdentity   ( $identity );
     public function getIdentity   ();

     public function checkAcl      ( $action );

     public function setAcl        ( JKY_Acl_Interface $acl );
     public function getAcl        ();
}