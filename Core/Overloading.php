<?php

/**
 * Core\Overloading
 *
 * Simple Session that we'll add more functionality to soon enough.
 */

namespace Core;


class Overloading
{
    protected $data = array();

    protected function __construct(){} /// Приватный конструктор ограничивает реализацию getInstance ()
    protected function __clone(){} /// Ограничивает клонирование объекта

    protected static $_instance = null;
    public static function Init()
    {
        if( is_null(static::$_instance) ) static::$_instance = new static();
        return static::$_instance;
    }
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if( array_key_exists($name,$this->data) )
        {
            return $this->data[$name];
        }
        ///$trace = debug_backtrace();
        ///trigger_error('Неопределенное свойство в __get(): ' . $name . ' в файле ' . $trace[0]['file'] . ' на строке ' . $trace[0]['line'], E_USER_NOTICE);
        return NULL;
    }
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}