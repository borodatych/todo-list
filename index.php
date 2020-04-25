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


/// PSR-0 Loader
require_once ROOT.'/Core/AutoLoaderPSR0.php';
/// PSR-4 Loader
///require_once ROOT.'/Core/AutoLoaderPSR4.php';
///$loader = new \Core\AutoLoaderPSR4();
///$loader->addNamespace('Core\\', __DIR__.'/Core/');
///$loader->register();
/// Попытка подрубить Composer не увенчалась успехом
///$loader = require_once __DIR__ . '/vendor/autoload.php';
///$loader->add('Core\\Route\\', __DIR__.'/Core/Route/');
///$loader->add('Core\Database', '/Core/Database');
///$loader->add('Core\\', __DIR__.'/Core/');
///$loader->add('Core\\','');
///$loader->register();


/// Include important files
require_once ROOT.'/App/Routes.php';
require_once ROOT.'/App/Config.php';

try
{
    /// Setup the database connection
    \Core\Database::$db =
        new \Core\Database(
        $config[$conf]['db']['host'],
        $config[$conf]['db']['user'],
        $config[$conf]['db']['pass'],
        $config[$conf]['db']['base'],
        $config[$conf]['db']['port'],
        $config[$conf]['db']['sock']
    );
    ///\Core\Database::exec("SET NAMES 'cp1251'");
    \Core\Database::exec("SET NAMES 'utf8'");

    /// Setup the routes
    $route = new \Core\Route();
    $route->init();
}
///catch(PDOException $e)
///{
///    echo 'Database Error: ' . $e->getMessage() . "\n<br />";
///    echo 'File: ' . $e->getFile() . "\n<br />";
///    echo 'Line: ' . $e->getLine();
///}
catch(Exception $e)
{
    echo 'Error: ' . $e->getMessage() . "\n<br />";
    echo 'File: ' . $e->getFile() . "\n<br />";
    echo 'Line: ' . $e->getLine();
}


if( \Core\Database::$db ) \Core\Database::$db->close();