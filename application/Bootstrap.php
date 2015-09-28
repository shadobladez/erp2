<?
/**
 * The application bootstrap used by Zend_Application
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class     Bootstrap 
extends   Zend_Application_Bootstrap_Bootstrap {

protected function _initView() {
     // Initialize view
     $view = new Zend_View();
#    $view->doctype( 'XHTML1_STRICT' );
     $view->doctype( 'XHTML1_TRANSITIONAL' );
     $view->headTitle( SITE_NAME );
     $view->skin = 'blues';
 
     // Add it to the ViewRenderer
     $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'ViewRenderer' );
     $viewRenderer->setView( $view );
 
     // Return it, so that it can be stored by the bootstrap
     return $view;
}

protected function _initAutoload() {
     //   Add autoloader empty namespace
     $autoLoader = Zend_Loader_Autoloader::getInstance();
     $autoLoader->registerNamespace( 'JKY_' );
     $resourceLoader = new Zend_Loader_Autoloader_Resource( array
          ( 'basePath'        => APPLICATION_PATH
          , 'namespace'       => ''
          , 'resourceTypes'   => array
               ( 'form'       => array( 'path' =>       'forms/', 'namespace' =>  'Form_' )
               , 'model'      => array( 'path' =>      'models/', 'namespace' => 'Model_' )
//               , 'model'      => array( 'path' =>      'models/', 'namespace' => '' )
//               , 'controller' => array( 'path' => 'controllers/', 'namespace' => 'Controller_' )
//               , 'controller' => array( 'path' => 'controllers/', 'namespace' => '' )
               )
          )
     );

     //   Return it so that it can be stored by the bootstrap
     return $autoLoader;
}

/**
 * @var Zend_Log
 */
protected $_logger;

/**
 * @var Zend_Application_Module_Autoloader
 */
protected $_resourceLoader;

/**
 * @var Zend_Controller_Front
 */
public    $frontController;

     /**
      * Configure the pluginloader cache
      */
     protected function X_initPluginLoaderCache() {
          if(  'production' == $this->getEnvironment() ) {
               $classFileIncCache = APPLICATION_PATH . '/../data/cache/pluginLoaderCache.php';
               if(  file_exists( $classFileIncCache )) {
                    include_once $classFileIncCache;
               }
               Zend_Loader_PluginLoader::setIncludeFileCache( $classFileIncCache );
          }
     }

/**
 * Setup the logging
 */
protected function _initLogging() {
     $this->bootstrap( 'frontController' );
     $logger = new Zend_Log();

     $writer = 'production' == $this->getEnvironment()
          ? new Zend_Log_Writer_Stream( APPLICATION_PATH . '/../logs/app.log' )
          : new Zend_Log_Writer_Firebug()
          ;
     $logger->addWriter( $writer );

     if(  'production' == $this->getEnvironment() ) {
          $filter = new Zend_Log_Filter_Priority( Zend_Log::CRIT );
          $logger->addFilter( $filter );
     }

     $this->_logger = $logger;
     Zend_Registry::set( 'log', $logger );

$this->_logger->info( 'Pat Jan - test firebug' );
}

/**
 * Add the config to the registry
 */
protected function _initConfig() {
     if( !Zend_Registry::isRegistered( 'config' )) {
          Zend_Loader::loadClass( 'Zend_Config_Ini' );
#         $config = new Zend_Config_Ini( APPLICATION_PATH . '/config.ini', APPLICATION_ENV );
          $config = new Zend_Config_Ini( APPLICATION_PATH . '/config.ini', ENVIRONMENT );
          Zend_Registry::set( 'config', $config );
     }
}


     /**
      * Configure the default modules autoloading, here we first create
      * a new module autoloader specifiying the base path and namespace
      * for our default module. This will automatically add the default
      * resource types for us. We also add two custom resources for Services
      * and Model Resources.
      */
     protected function X_initDefaultModuleAutoloader() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );

//          $this->_resourceLoader = new Zend_Application_Module_Autoloader( array
//               ( 'namespace' => 'Iads'
//               , 'basePath'  => APPLICATION_PATH . '/modules/iads'
//               ,
//               )
//          );

          $this->_resourceLoader->addResourceTypes( array
               ( 'modelResource' => array 
                    ( 'namespace'  => 'Resource'
                    , 'path'       => 'models/resources'
                    ,
                    )
               )
          );
     }

     /**
      * Setup locale
      */
     protected function X_initLocale() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );

//        $locale = new Zend_Locale( 'en_GB' );
          $locale = new Zend_Locale( Zend_Locale::BROWSER );
          Zend_Registry::set( 'Zend_Locale', $locale );
     }

     /**
      * Setup the database profiling
      */
     protected function X_initDbProfiler() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );
        
          if(  'production' !== $this->getEnvironment() ) {
               $this->bootstrap( 'db' );
               $profiler = new Zend_Db_Profiler_Firebug( 'All DB Queries' );
               $profiler->setEnabled( true );
               $this->getPluginResource( 'db' )->getDbAdapter()->setProfiler( $profiler );
          }
     }

/**
 * Init the db
 */
protected function _initDb() {

     if( !Zend_Registry::isRegistered( 'db' )) {  
#         $config = new Zend_Config_Ini( APPLICATION_PATH . '/config.ini', APPLICATION_ENV );
          $config = new Zend_Config_Ini( APPLICATION_PATH . '/config.ini', ENVIRONMENT );
          $db     = Zend_Db::factory( $config->resources->db);
          Zend_Db_Table_Abstract::setDefaultAdapter( $db );
          Zend_Registry::set( 'db', $db );
     }

//   setup language
     if( !Zend_Registry::isRegistered( 'translate' )) {
          $translate = new Zend_Translate( 'csv', SERVER_BASE . LANGUAGES . 'en.csv', 'en' );
          $actual    = $translate->getLocale();
//          $language  = get_session( 'language' );
          $language = Zend_Registry::isRegistered( 'language' ) ? Zend_Registry::get( 'language' ) : '';
          if(  $language == '' or $language == '*' ) {
               $language = ( substr( get_ip(), 0, 3 ) == '58.' ) ? 'zh-Hans' : LANGUAGE;
//               set_session( 'language', $language );
               Zend_Registry::set( 'language' , $language  );
          }
          $translate->addTranslation( SERVER_BASE . LANGUAGES . $language . '.csv', substr( $language, 0, 2 ));
          $locale = new Zend_Locale( 'en_US' );
          Zend_Registry::set( 'translate', $translate );
     }
}

/**
 * Setup the view
 */
protected function _initViewSettings() {
     $this->_logger->info( 'Bootstrap ' . __METHOD__ );

     $this->bootstrap( 'view' );

     $this->_view = $this->getResource( 'view' );

     //   add global helpers
     $this->_view->addHelperPath( APPLICATION_PATH . '/views/helpers', 'Zend_View_Helper' );

     //   set encoding and doctype
     $this->_view->setEncoding( 'UTF-8' );
#    $this->_view->doctype    ( 'XHTML1_STRICT' );
     $this->_view->doctype    ( 'XHTML1_TRANSITIONAL' );

     //   set the content type and language
     $this->_view->headMeta   ()->appendHttpEquiv( 'Content-Type', 'text/html; charset=UTF-8' );
     $this->_view->headMeta   ()->appendHttpEquiv( 'Content-Language', 'en-US' );

     //   set css links and a special import for the accessibility styles
//     $this->_view->headStyle  ()->setStyle( '@import "/css/access.css";' );
//     $this->_view->headLink   ()->appendStylesheet( '/css/reset.css'    );
//     $this->_view->headLink   ()->appendStylesheet( '/css/main.css'     );
//     $this->_view->headLink   ()->appendStylesheet( '/css/form.css'     );
//     $this->_view->headLink   ()->appendStylesheet( '/css/layout.css'   );
//     $this->_view->headLink   ()->appendStylesheet( '/css/application.css' );

     //   set general scripts
     $this->_view->headScript ()->appendFile( '/js/application.js?ver=4.1'  );
//     $this->_view->headScript ()->appendFile( '/js/prototype.js'            );
//     $this->_view->headScript ()->appendFile( '/js/effects.js'              );
     $this->_view->headScript ()->appendFile( '/js/jquery.js'               );           // jquery must be after protoype
     $this->_view->headScript ()->appendFile( '/js/jquery_ui.js'            );             

     //   setting the site in the title
//     $this->_view->headTitle( SITE_NAME );

     //   setting a separator string for segments:
     $this->_view->headTitle()->setSeparator( ' - ' );
}

protected function _initDoctype() {
     $this->bootstrap( 'view' );
     $view = $this->getResource( 'view' );
#    $view->doctype( 'XHTML1_STRICT' );
     $view->doctype( 'XHTML1_TRANSITIONAL' );
}

     /**
      * Add required routes to the router
      */
     protected function X_initRoutes() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );
          $this->bootstrap( 'frontController' );

          $router = $this->frontController->getRouter();

          // Admin context route
          $route = new Zend_Controller_Router_Route
               ( 'admin/:module/:controller/:action/*'
               , array
                    ( 'action'          => 'index'
                    , 'controller'      => 'admin'
                    , 'module'          => 'iads'
                    , 'isAdmin'         =>  true
                    )
               );

          $router->addRoute( 'admin', $route );

          // catalog category product route
          $route = new Zend_Controller_Router_Route
               ( 'catalog/:categoryIdent/:productIdent'
               , array
                    ( 'action'          => 'view'
                    , 'controller'      => 'catalog'
                    , 'module'          => 'iads'
                    , 'categoryIdent'   => ''
                    ,
                    )
               , array
                    ( 'categoryIdent'   => '[a-zA-Z-_0-9]+'
                    , 'productIdent'    => '[a-zA-Z-_0-9]+'
                    )
               );

          $router->addRoute( 'catalog_category_product', $route );

          // catalog category route
          $route = new Zend_Controller_Router_Route
               ( 'catalog/:categoryIdent/:page'
               , array
                    ( 'action'          => 'index'
                    , 'controller'      => 'catalog'
                    , 'module'          => 'iads'
                    , 'categoryIdent'   => ''
                    , 'page'            =>  1
                    )
               , array
                    ( 'categoryIdent'   => '[a-zA-Z-_0-9]+'
                    , 'page'            => '\d+'
                    )
               );

          $router->addRoute( 'catalog_category', $route );
     }

     /**
      * Add Controller Action Helpers
      */
     protected function X_initActionHelpers() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );
          Zend_Controller_Action_HelperBroker::addHelper( new JKY_Controller_Helper_Acl() );
          Zend_Controller_Action_HelperBroker::addHelper( new JKY_Controller_Helper_RedirectCommon() );
          Zend_Controller_Action_HelperBroker::addHelper( new JKY_Controller_Helper_Service() );
     }

     /**
      * Init the db metadata and paginator caches
      */
     protected function X_initDbCaches() {
          $this->_logger->info( 'Bootstrap ' . __METHOD__ );
          if(  'production' == $this->getEnvironment()) {
               // Metadata cache for Zend_Db_Table
               $frontendOptions = array( 'automatic_serialization' => true );
               $cache = Zend_Cache::factory( 'Core', 'Apc', $frontendOptions );
               Zend_Db_Table_Abstract::setDefaultMetadataCache( $cache );
          }
     }

     /**
      * Add gracefull error handling to the bootstrap process
      */
     protected function X_bootstrap( $resource = null ) {
          $errorHandling = $this->getOption( 'errorhandling' );
          try {
               parent::_bootstrap( $resource );
          } catch( Exception $exp ) {
               if(  true == (bool) $errorHandling[ 'graceful' ]) {
                    $this->__handleErrors( $exp, $errorHandling[ 'email' ]);
               } else {
                    throw $exp;
               }
          }
     }

     /**
      * Handle errors gracefully, this will work as long as the views,
      * and the Zend classes are available
      *
      * @param Exception $e
      * @param string $email
      */
     protected function X__handleErrors( Exception $exp, $email ) {
          header( 'HTTP/1.1 500 Internal Server Error' );
          $view = new Zend_View();
          $view->addScriptPath( dirname( __FILE__ ) . '/../views/scripts' );
          echo $view->render( 'fatalError.phtml' );

          if(  '' != $email ) {
               $mail = new Zend_Mail();
               $mail->setSubject( 'Fatal error in application ' . SITE_NAME );
               $mail->addTo( $email );
               $mail->setBodyText
                    ( "\n" . $exp->getFile()
                    . "\n" . $exp->getMessage()
                    . "\n" . $exp->getTraceAsString()
                    );
               @$mail->send();
          }
     }

     /**
      * Add graceful error handling to the dispatch, this will handle
      * errors during Front Controller dispatch.
      */
     public function Xrun() {
          $errorHandling = $this->getOption( 'errorhandling' );
          try {
               parent::run();
          } catch( Exception $exp ) {
               if(  true == (bool) $errorHandling[ 'graceful' ]) {
                    $this->__handleErrors( $exp, $errorHandling[ 'email' ]);
               } else {
                    throw $exp;
               }
          }
     }
}