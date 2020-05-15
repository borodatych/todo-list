<?php

/**
 * HTTP helper.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;


use Core\Config;

class HTTP
{
    public static function setCode($code=200)
    {
        /// http://php.net/manual/ru/function.http-response-code.php
        /// https://ru.wikipedia.org/wiki/Список_кодов_состояния_HTTP
        http_response_code($code);
    }
    public static function error503($time=300)
    {
        header("HTTP/1.1 503 Service Temporarily Unavailable");
        header("Status: 503 Service Temporarily Unavailable");
        header("Retry-After: $time");
    }
	public static function redirect($uri,$code=200) /// С 400 кодом не редиректится
	{
        // 301 Moved Permanently
        // 302 Found
        // 303 See Other
        // 307 Temporary Redirect

        session_write_close();
        header("Location: $uri",TRUE,$code);
        exit();
	}
    private static function __changeSchema($SCHEMA='')
    {
        if( $SCHEMA )
        {
            $HOST = Config::item('host');
            $REQUEST_URI = Arr::get($_SERVER,'REQUEST_URI');
            $HOST_URI = $SCHEMA.'://'.$HOST.$REQUEST_URI;
            HTTP::redirect($HOST_URI,301);
        }
    }
    public static function toHTTP(){ static::__changeSchema('http'); }
    public static function toHTTPS(){ static::__changeSchema('https'); }
    public static function referrer()
    {
        $HTTP_REFERER = $_SERVER['HTTP_REFERER'];
        $refData = parse_url($HTTP_REFERER);
        $referrer = Arr::get($refData,'path');
        return $referrer;
    }
    /** Ипользуется в Core\Controller */
    public static function request($url,$params=array()){
        $body = Arr::get($params,'body');
        $auth = Arr::get($params,'auth');
        $post = Arr::get($params,'post');
        $header = Arr::get($params,'header');
        $headers = Arr::get($params,'headers');
        $uAgent  = Arr::get($params,'uAgent',"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1");
        $uaForce = Arr::get($params,'uaForce');
        $timeouts = Arr::get($params,'timeouts','15:25');
        /// Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1
        /// Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5
        /// Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36
        $userAgent = ($ua=@$_SERVER['HTTP_USER_AGENT']) ?$ua :$uAgent;
        if( $uaForce ) $userAgent = $uAgent;
        /// TimeOuts
        $arrTimeouts = explode(':',$timeouts);
        $toConn = Arr::get($arrTimeouts,0,4);
        $toExec = Arr::get($arrTimeouts,1,7);
        /////////////////////////////////////////////////////////
        /// http://php.net/manual/ru/function.curl-setopt.php ///
        $ch = curl_init($url);                                /// Замена $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $header);            /// Возвращает заголовки?
        ///curl_setopt($ch, CURLOPT_NOBODY, 1);               /// Читать ТОЛЬКО заголовок без тела
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);           /// Возвращает веб-страницу
        curl_setopt($ch, CURLOPT_USERAGENT,$userAgent);       /// ЮзерАгент
        curl_setopt($ch, CURLOPT_REFERER, "api://yulsun.ru"); /// Рефер
        ///curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);        /// Переходит по редиректам
        ///curl_setopt($ch, CURLOPT_MAXREDIRS, 10);           /// останавливаться после 10-ого редиректа
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$toConn);     /// Таймаут на соединение
        curl_setopt($ch, CURLOPT_TIMEOUT,$toExec);            /// Таймайт на выполнение
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 0);       /// DEF 120sec
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);      /// Отключаем проверку сертификата
        /////////////////////////////////////////////////////////
        if( $body ){
            ///$body = array('name' => 'Foo', 'file' => '@/home/user/test.png');
            $body = (is_array($body)) ? http_build_query($body) : $body;
            curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        }///else curl_setopt($ch, CURLOPT_POST,1);
        if( $auth ){
            /// $auth="login:password";
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
        }
        if( $headers ) curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        if( $post ) curl_setopt($ch, CURLOPT_POST,1);///Помоему лишняя опция [2016.12.29]
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public static function createURI($query)
    {
        return Config::item('url').DS.$query;
    }
    public static function deleteGET($url, $name, $amp = FALSE)
    {
        if( $amp ) $url = str_replace("&amp;", "&", $url);
        list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
        parse_str($qs_part, $qs_vars);
        unset($qs_vars[$name]);
        if( count($qs_vars) > 0 )
        {
            $url = $url_part."?".http_build_query($qs_vars);
            if( $amp ) $url = str_replace("&", "&amp;", $url);
        }
        else $url = $url_part;
        return $url;
    }
    /** 301 редирект с дублий от encoded */
    public static function deleteDuplicates()
    {
        $REQUEST_URI = Arr::get($_SERVER,'REQUEST_URI');
        /// Ёбля с символами, видимо от такого никуда не деться, если захочешь уйти от дублей
        if( strpos($REQUEST_URI,'%20')!==FALSE || strpos($REQUEST_URI,'%26')!==FALSE || strpos($REQUEST_URI,'%2A')!==FALSE
            || strpos($REQUEST_URI,'%28')!==FALSE || strpos($REQUEST_URI,'%29')!==FALSE || strpos($REQUEST_URI,'%2B')!==FALSE )
        {
            $REQUEST_URI = urldecode($REQUEST_URI);
            $REQUEST_URI = str_replace([' '],['+'],$REQUEST_URI);
            ///print'<pre>';print_r($REQUEST_URI);print'</pre>';exit;
            static::redirect($REQUEST_URI,301);
        }
    }
    /** Получение переменной из POST или GET */
    public static function receive($varname,$def=""){
        $dt = @$_POST[$varname];
        /// Это что бы не поломалось. НО! Лучше заюзать jPost + Arr::get
        if( !$dt ){
            $POST = file_get_contents("php://input");
            $POST = json_decode($POST);
            $dt = @$POST->{$varname};
        }
        if( !$dt ) $dt = @$_GET[$varname];

        if( gettype($dt)=='string' ) $dt = htmlentities($dt,ENT_QUOTES,"UTF-8");
        else if( gettype($dt)=='array' ){
            array_walk_recursive($dt,function(&$value){
                $value = htmlentities($value,ENT_QUOTES,"UTF-8");
            });
        }
        return ( $dt ) ?$dt :$def;
    }
    /** Ипользуется в Core\Controller */
    public static function isAjax(){
        if( !isset( $_SERVER[ "HTTP_X_REQUESTED_WITH" ] ) || $_SERVER[ "HTTP_X_REQUESTED_WITH" ] != "XMLHttpRequest" ) return FALSE;
        return TRUE;
    }
}
