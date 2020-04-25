<?php

/**
 * Core\Database
 *
 * Simple MySQLi wrapper that we'll add more functionality to soon enough.
 */

namespace Core;


class Database extends \MySQLi
{

    public static $db = NULL;
    /**
     * Database::__construct()
     *
     * Calls the MySQLi construct to connect to the database
     */
    public function __construct($host,$user,$pass,$base,$port,$sock)
    {
        parent::__construct($host,$user,$pass,$base,$port,$sock);
        ///self::$db = $this->mysqli($host,$user,$pass,$base,$port,$sock);
    }
    public static function exec($sql,$db='db',$ex=FALSE)
    {
        $_db = ( $ex ) ? $db : self::${$db};
        $res = $_db->query($sql);
        return $res;
    }
    public static function escape($str,$db='db',$ex=FALSE)
    {
        $_db = ( $ex ) ? $db : self::${$db};
        $str = $_db->escape_string($str);
        return $str;
    }
    public static function fetch($res)
    {
        $row = $res->fetch_assoc();
        $res->free();
        return $row;
    }
    public static function clear($db='db',$ex=FALSE)
    {
        $_db = ( $ex ) ? $db : self::${$db};
        while( $_db->more_results() && $_db->next_result() )
        {
            $extraResult = $_db->use_result();
            if( $extraResult instanceof mysqli_result ) $extraResult->free();
        }
        ///do{} while ( $L->more_results() && $L->next_result() );///однострочный вариант
    }
    public static function ddate($format,$db='db',$ex=FALSE)
    {
        if( $format=='Y.m.d') $format = "%Y.%m.%d";
        if( $format=='d.m.y') $format = "%d.%m.%y";
        if( $format=='d/m/Y') $format = "%d/%m/%Y";
        if( $format=='G')     $format = "%k";
        if( $format=='i')     $format = "%i";
        if( $format=='w')     $format = "%w";

        $sql = "select date_format(now(),'$format')";

        $_db = ( $ex ) ? $db : self::${$db};
        $res = $_db->query($sql);
        $row = $res->fetch_array();
        $date = $row[0];
        return $date;
    }
}