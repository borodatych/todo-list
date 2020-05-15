<?php
/**
 * Core\Config
 * 
 * Get config items from the config without having to
 * use globals in our other class methods. May have
 * more functionality soon.
 */
namespace Core;

class Config
{
    public static $conf = 'default';
    public static $config = [];

    /**
     * Config::Item
     *
     * Return the defined config setting if config setting is set
     * @param string $item_name
     * @return string
     */
    public static function item($item_name)
    {
        if( isset(static::$conf) && isset(static::$config[static::$conf][$item_name]) )
        {
            return static::$config[static::$conf][$item_name];
        }

        return null;
    }
    
}
