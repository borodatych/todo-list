<?php

/**
 * Core\Core
 *
 * От этого класса в итоге наследуются все
 * К примеру, режим отладки включать
 * Уже забыл зачем затял
 *
 * Использовать так:
 *      Включаем:
 *          \Core\Core::$debug = TRUE;
 *      Сохраняем:
 *          if ( TRUE === static::$debug ) static::$console['request'] = "ВАШ ТЕКСТ";
 *      Используем:
 *          if ( TRUE === static::$debug ) var_dump(\Core\Core::$console);
 */

namespace Core;


class Core extends Overloading
{
    public static $debug = FALSE; /// Включить режим отладки
    public static $console = [];  /// При отладке складывается инфа
    public function __construct() {}


    /**
     * Instance Singleton Object for Every Class
     * Used to Extend the Class
     *  ~ get_called_class ~
     */
    public static $_instance;
    public static function instance($arr=FALSE){
        if ( get_class(static::$_instance) !== get_called_class() ) static::$_instance = new static($arr);
        return static::$_instance;
    }
}