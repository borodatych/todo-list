<?php

/**
 * Core\Session
 *
 * Simple Session that we'll add more functionality to soon enough.
 */

namespace Core;


class Session
{
    private $__session = array();

    protected function __construct() /// Приватный конструктор ограничивает реализацию getInstance ()
    {
        session_start();
        $this->__session = &$_SESSION;
    }
    protected function __clone(){} /// Ограничивает клонирование объекта

    public static $_instance = null;
    public static function Init()
    {
        if( is_null(static::$_instance) ) static::$_instance = new static();
        return static::$_instance;
    }
    public function fetch(){
        return $this->__session;
    }
    public function __set($name, $value)
    {
        $this->__session[$name] = $value;
        $_SESSION[$name] = $value;
    }

    public function __get($name)
    {
        if( array_key_exists($name,$this->__session) )
        {
            return $this->__session[$name];
        }
        ///$trace = debug_backtrace();
        ///trigger_error('Неопределенное свойство в __get(): ' . $name . ' в файле ' . $trace[0]['file'] . ' на строке ' . $trace[0]['line'], E_USER_NOTICE);
        return NULL;
    }
    public function __isset($name)
    {
        return isset($this->__session[$name]);
    }
    public function __unset($name)
    {
        unset($this->__session[$name]);
        unset($_SESSION[$name]);
    }
}