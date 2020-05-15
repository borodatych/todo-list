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


use \Core\Config;
use \Core\Database;
use \Core\Route;

try
{
    /// Setup the database connection
    Database::$db = new Database(
        Config::$config[Config::$conf]['db']['host'],
        Config::$config[Config::$conf]['db']['user'],
        Config::$config[Config::$conf]['db']['pass'],
        Config::$config[Config::$conf]['db']['base'],
        Config::$config[Config::$conf]['db']['port'],
        Config::$config[Config::$conf]['db']['sock']
    );
    Database::exec("SET NAMES 'utf8'");

    /// Setup the routes
    $route = new Route();
    $route->init();
}
/*/
catch(PDOException $e)
{
    echo 'Database Error: ' . $e->getMessage() . "\n<br />";
    echo 'File: ' . $e->getFile() . "\n<br />";
    echo 'Line: ' . $e->getLine();
}
//*/
catch(Exception $e)
{
    echo 'Error: ' . $e->getMessage() . "\n<br />";
    echo 'File: ' . $e->getFile() . "\n<br />";
    echo 'Line: ' . $e->getLine();
}


if( Database::$db ) Database::$db->close();
