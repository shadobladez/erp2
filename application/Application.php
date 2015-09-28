<?
require_once 'Constant.php';
require_once  'Utility.php';
require_once 'Specific.php';

//   report errors
error_reporting( E_ALL );

ini_set( 'display_startup_errors'  , 'on' );
ini_set( 'display_errors'          , 'on' );

date_default_timezone_set( 'America/Sao_Paulo' );

//   Define path to application directory
defined( 'APPLICATION_PATH' ) or define( 'APPLICATION_PATH', realpath( dirname( __FILE__ )));

//   Ensure library/ is on include_path
set_include_path( implode( PATH_SEPARATOR, array( realpath( APPLICATION_PATH . LIBRARY ), get_include_path() )));

//   Zend_Application
require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Application.php';

//   Create application, bootstrap, and run
$application = new Zend_Application( ENVIRONMENT, APPLICATION_PATH . '/config.ini' );
$application->bootstrap();
$application->run();
?>
