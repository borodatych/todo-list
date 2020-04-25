<?php

/**
 * Text helper class. Provides additional formatting methods that for working with string/text.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;


class Text
{

    public static function htmlSpecialChars( $string, $flags=ENT_QUOTES, $encoding="UTF-8" ){
        return htmlspecialchars( $string, $flags, $encoding );
    }
    public static function htmlEntities( $string, $flags=ENT_QUOTES, $encoding="UTF-8" ){
        return htmlentities( $string, $flags, $encoding );
    }

    /**
     * Cut Symbols from String on the Array with Positions
     *
     *      $oHash = Core::cutSymbol($hash,[5,13]);
     *
     * @param   string  $str    input string
     * @param   array   $pos    array with symbol position
     * @return  object
     */
    public static function cutSymbol($str=NULL,$pos=NULL){
        if( $str===NULL || $pos===NULL ) return false;
        $symbols = array(); $symbol = FALSE;
        if( is_array($pos) ){
            $i = -1; foreach( $pos AS $p ){ ++$i;
                $p = $p-$i;
                if( $p>strlen($str)+1 ) return FALSE;
                $symbol .= $str{$p};
                $symbols[] = $str{$p};
                $str = substr_replace($str,'',$p,1);
            }
            $string = $str;
        }
        else{
            if( $pos>strlen($str)+1 ) return FALSE;
            $symbol .= $str{$pos};
            $symbols[] = $symbol;
            $string = substr_replace($str,'',$pos,1);
        }
        return json_decode( json_encode( ["symbol"=>$symbol,"symbols"=>$symbols,"string"=>$string] ) );
    }

    /**
     * Split String
    */
    public static function split($str,$del){
        return implode($del,preg_split('//',$str,-1,PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Convert WINDOWS1251 from UTF-8
     *
     * @param mixed $text
     * @param bool $force
     * @return mixed
     */
    public static function GetTextCP1251($text,$force=FALSE)
    {
        if( is_array($text) || is_object($text) )
        {
            foreach( $text as &$value ) $value = self::GetTextCP1251($value);
            return $text;
        }
        if( mb_check_encoding($text, "UTF-8") || $force )
        {
            $text = mb_convert_encoding( $text, "WINDOWS-1251", "UTF-8" );
        }
        return $text;
    }

    /**
     * Convert WINDOWS-1251 to UTF-8
     *
     * @param mixed $text
     * @param bool $force
     * @return mixed
     */
    public static function GetTextUTF8($text,$force=FALSE){
        if( is_array($text) || is_object($text) )
        {
            foreach( $text AS &$value ) $value = self::GetTextUTF8($value);
            return $text;
        }
        if( mb_check_encoding( $text, "WINDOWS-1251" ) || $force )
        {
            $text = mb_convert_encoding( $text, "UTF-8", "WINDOWS-1251" );
        }
        return $text;
    }

    public static function correctUrlEncode($string)
    {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, $string);
    }
    public static function myUrlEncode($string)
    {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }
    public static function huuUrlEncode($string)
    {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }


    public static function ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) . mb_substr($string, 1, mb_strlen($string, $enc), $enc);
        ///return mb_convert_case($string, MB_CASE_TITLE, $enc);
    }
    public static function upper($string, $enc = 'UTF-8')
    {
        return mb_strtoupper($string,$enc);
    }
    public static function lower($string, $enc = 'UTF-8')
    {
        return mb_strtolower($string,$enc);
    }
    public static function lastSymbol($str,$encoding='UTF-8')
    {
        return mb_substr($str,-1,NULL,$encoding);
    }

    private static $__rus = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','В',
        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','в');
    private static $__lat=array('A','B','V','G','D','E','E','GH','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','C','CH','SH','SCH','Y','Y','Y','E','YU','YA','W',
        'a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','w');
    public static function lat2rus($text)
    {
        return str_replace(static::$__lat,static::$__rus,$text);
    }
    public static function rus2lat($text)
    {
        return str_replace(static::$__rus,static::$__lat,$text);
    }
}
