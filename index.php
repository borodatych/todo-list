<?php header( 'Content-type: text/html; charset=utf-8' );

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

///define('DEBUG',FALSE);
///define('DEBUG',TRUE);

define ('DS', "/");
define ('T', ".");

/// Get root directory
define('ROOT', str_replace('\\', '/', __DIR__));
/// Site ID
define ('SITE_ID', "201705181235");


/// Include PSR-0 Loader
require_once ROOT.'/Core/AutoLoaderPSR0.php';

/// Include PSR-4 Loader
require_once ROOT.'/Core/AutoLoaderPSR4.php';

/// Include important files
require_once ROOT.'/App/Routes.php';
require_once ROOT.'/App/Config.php';


try
{
    /// Setup the routes
    $route = new \Core\Route();
    $route->init();
}
catch(Exception $e)
{
    echo 'Error: ' . $e->getMessage() . "\n<br />";
    echo 'File: ' . $e->getFile() . "\n<br />";
    echo 'Line: ' . $e->getLine();
}
