<?php

use \Core\Config;

/// current config that we'll be using
Config::$conf = 'default';

/// url path to application
Config::$config['default']['scheme'] = @$_SERVER['REQUEST_SCHEME'];
Config::$config['default']['host'] = @$_SERVER['HTTP_HOST'];
Config::$config['default']['url'] = Config::$config['default']['scheme'].'://'.Config::$config['default']['host'];


/**
 * Database Settings
 *
 * We use a simple wrapper on top of PDO so refer to the manual
 * for connecting using the database driver that you migth use.
 * By default we're using the mysql dsn.  Pretty straight forward
 *
 *
 * PDO
 * Config::$config['default']['db']['dsn']      = 'mysql:host=localhost;dbname=base';
 * Config::$config['default']['db']['user']     = 'user';
 * Config::$config['default']['db']['password'] = 'pass';
 */

/// Default Error Pages
Config::$config['default']['401'] = '401Lite';
Config::$config['default']['404'] = '404Lite';

/// Revision | Reset Cash
Config::$config['default']['revision']['js']   = 1;
Config::$config['default']['revision']['css']  = 1;

/// MySQL Connect
Config::$config['default']['db']['host'] = 'db_host';
Config::$config['default']['db']['user'] = 'db_user';
Config::$config['default']['db']['pass'] = 'db_pass';
Config::$config['default']['db']['base'] = 'db_name';
Config::$config['default']['db']['port'] = 'db_port';
Config::$config['default']['db']['sock'] = '/var/run/mysqld/mysqld.sock';


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
