<?php

/// current config that we'll be using
$conf = 'default';

/// url path to application
$config['default']['scheme'] = @$_SERVER['REQUEST_SCHEME'];
$config['default']['host'] = @$_SERVER['HTTP_HOST'];
$config['default']['url'] = $config['default']['scheme'].'://'.$config['default']['host'];


/**
 * Database Settings
 *
 * We use a simple wrapper on top of PDO so refer to the manual
 * for connecting using the database driver that you migth use.
 * By default we're using the mysql dsn.  Pretty straight forward
 *
 *
 * PDO
 * $config['default']['db']['dsn']      = 'mysql:host=localhost;dbname=base';
 * $config['default']['db']['user']     = 'user';
 * $config['default']['db']['password'] = 'pass';
 */

/// Default Error Pages
$config['default']['401'] = '401Lite';
$config['default']['404'] = '404Lite';

/// Revision | Reset Cash
$config['default']['revision']['js']   = 1;
$config['default']['revision']['css']  = 1;

/// MySQL Connect
$config['default']['db']['host'] = 'db_host';
$config['default']['db']['user'] = 'db_user';
$config['default']['db']['pass'] = 'db_pass';
$config['default']['db']['base'] = 'db_name';
$config['default']['db']['port'] = 'db_port';
$config['default']['db']['sock'] = '/var/run/mysqld/mysqld.sock';


/**
 *---------------------------------------------------------------------------------------------------------------------*
 * ПОДГРУЗКА СТОРОННИХ БИБЛИОТЕК:
 * 1. Можно создать хелпер вида: App/Helpers/INDAConvert.php , App/Helpers/MobileDetect.php
 * 2. Можно использовать конструкцию вида:
 *      $loader = new AutoLoaderPSR4();
 *      $loader->addNamespace('PHPHtmlParser', ROOT.'/App/Libraries/PHPHtmlParser');
 *      $loader->addNamespace('stringEncode', ROOT.'/App/Libraries/stringEncode');
 *      $loader->register();
 *      $dom = new \PHPHtmlParser\Dom();
 *      $dom->load($rDataVIN);
 *---------------------------------------------------------------------------------------------------------------------*
 * СМОТРЕТЬ КАК СТРОИТСЯ МАРШРУТ:
 * Core/Route.php => loadController
 *---------------------------------------------------------------------------------------------------------------------*
 */



