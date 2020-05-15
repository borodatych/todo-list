<?php

/**
 * Core\Controller
 *
 * Controller parent class, no real functionality yet.
 * I decided to not have any loader classes as it would
 * be just as much coding to create a new instance of a model
 * than to call $this->load->library or $this->load->model
 * everytime.
 */

namespace Core;

use App\Helpers\Arr;
use App\Helpers\HTTP;

class Controller extends Overloading
{
    /**
     * Controller::__construct()
     *
     * Overloading
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Debug
     */
    protected function p(){
        $numArgs = func_num_args();
        $argList = func_get_args();
        ///if( $numArgs > 2 ){
            foreach( $argList AS $arg ){ print'<pre>';print_r($arg);print'</pre>'; }
        ///}
        ///print'<pre>';print_r($arr);print'</pre>';
    }
    protected function e()
    {
        $argList = func_get_args();
        foreach( $argList AS $arg ){ print'<pre>';print_r($arg);print'</pre>'; }
        exit;
    }
    /**
     * метод проверяет, была ли отправлена форма по наличию имени кнопки в массиве $_POST
     * кроме того происходит проверка referer
     * Возвращает TRUE при выполнении условий и значение, если оно есть, в объект
     */
    protected function btnPressed($buttonName){
        $matches = NULL;
        if( !isset($_POST[$buttonName]) ) return FALSE;
        if( !isset($_SERVER['HTTP_REFERER']) ) return FALSE; // проверка referer
        if( !preg_match('/^https?\:\/\/([^\/]+)\/.*$/i',$_SERVER['HTTP_REFERER'],$matches) ) return FALSE;
        if( $matches[1] != $_SERVER['HTTP_HOST'] ) return FALSE;
        $this->btn = $_POST[$buttonName];
        return TRUE;
    }
    /** На базе прошлой функции */
    protected function isFormSubmitted(){
        $matches = NULL;
        if( !isset($_SERVER['HTTP_REFERER']) ) return FALSE;
        if( !preg_match('/^https?\:\/\/([^\/]+)\/.*$/i',$_SERVER['HTTP_REFERER'],$matches) ) return FALSE;
        if( $matches[1] != $_SERVER['HTTP_HOST'] ) return FALSE;
        return TRUE;
    }
    /** Получение переменной из POST или GET */
    protected function receive($varname,$def=""){
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
    /** Получение данных из POST формата json в виде объекта */
    protected function jPost() {
        return json_decode(file_get_contents('php://input'), true);
    }
    /** jEcho - Return Response from API */
    protected function jEcho($msg,$exit=TRUE){
        if( !(is_string($msg) && is_object(json_decode($msg)) && (json_last_error() == JSON_ERROR_NONE) ? true : false) ) $msg = json_encode($msg);
        echo $msg;
        if( $exit ) exit;
    }
    protected function ajaxRequest(){
        return HTTP::isAjax(); /// Если нужно удалить хелпер, переносим код сюда
    }

    protected function request($url,$params=array()){
        return HTTP::request($url,$params);
    }
    /** This is Bot? */
    protected function isBot()
    {
        $bots = array(
            'bot', 'stackrambler', 'aportworm', 'isearch', 'yandex', 'yandexblog', 'mail.ru', 'adsbot-google', 'ia_archiver', 'check_http',
            'scooter/', 'ask jeeves', 'baiduspider+(', 'exabot/', 'fast enterprise crawler', 'fast-webcrawler/', 'http://www.neomo.de/', 'gigabot/',
            'mediapartners-google', 'google desktop', 'feedfetcher-google', 'googlebot', 'heise-it-markt-crawler', 'heritrix/1.', 'ibm.com/cs/crawler',
            'iccrawler - icjobs', 'ichiro/2', 'mj12bot/', 'metagerbot/', 'msnbot-newsblogs/', 'msnbot/', 'msnbot-media/', 'ng-search/',
            'http://lucene.apache.org/nutch/', 'nutchcvs/', 'omniexplorer_bot/', 'online link validator', 'psbot/0', 'seekbot/', 'sensis web crawler',
            'seo search crawler/', 'seoma [seo crawler]', 'seosearch/', 'snappy/1.1 ( http://www.urltrends.com/ )', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/',
            'synoobot/', 'crawleradmin.t-info@telekom.de', 'turnitinbot/', 'voyager/1.0', 'w3 sitesearch crawler', 'w3c-checklink/', 'w3c_*validator',
            'http://www.wisenutbot.com', 'yacybot', 'yahoo-mmcrawler/', 'yahoo! de slurp', 'yahoo! slurp', 'yahooseeker/'
        );
        $user_agent = strtolower( Arr::get($_SERVER,'HTTP_USER_AGENT') );
        ///$this->e($user_agent);
        if( $user_agent == "" ) return TRUE;
        foreach( $bots as $bot ) if( stripos($user_agent, $bot) !== FALSE ) return TRUE;
        return FALSE;
    }
    protected function ip()
    {
        /// В PHP так описывают(https://www.php.net/manual/ru/function.getenv.php):
        /// $ip = getenv('REMOTE_ADDR', true) ?: getenv('REMOTE_ADDR');
        if ( getenv ('REMOTE_ADDR'))               $user_ip = getenv ('REMOTE_ADDR');
        elseif ( getenv ('HTTP_FORWARDED_FOR'))    $user_ip = getenv ('HTTP_FORWARDED_FOR');
        elseif ( getenv ('HTTP_X_FORWARDED_FOR'))  $user_ip = getenv ('HTTP_X_FORWARDED_FOR');
        elseif ( getenv ('HTTP_X_COMING_FROM'))    $user_ip = getenv ('HTTP_X_COMING_FROM');
        elseif ( getenv ('HTTP_VIA'))              $user_ip = getenv ('HTTP_VIA');
        elseif ( getenv ('HTTP_XROXY_CONNECTION')) $user_ip = getenv ('HTTP_XROXY_CONNECTION');
        elseif ( getenv ('HTTP_CLIENT_IP'))        $user_ip = getenv ('HTTP_CLIENT_IP');
        else $user_ip = 'unknown';
        ///
        if( 15 < strlen ($user_ip) )
        {
            $ar = explode (',', $user_ip);
            for( $i = sizeof($ar)-1; $i > 0; $i-- )
            {
                if( $ar[$i]!='' && !preg_match ('/[a-zA-Zа-яА-Я]/', $ar[$i]) )
                {
                    $user_ip = trim($ar[$i]);
                    break;
                }
                if( $i== sizeof ($ar)-1 ) $user_ip = 'unknown';
            }
        }
        if ( preg_match ('/[a-zA-Zа-яА-я]/', $user_ip) ) $user_ip = 'unknown';
        return $user_ip;
    }
    protected function regionByIP()
    {
        $is_bot = preg_match(
            "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
            Arr::get($_SERVER,'HTTP_USER_AGENT')
        ); /// Этого примера маловато, перед отрабатывает наш
        if( $is_bot ) return FALSE;
        $ip = $this->ip();
        ///$_url = "https://api.sypexgeo.net/json/$ip";
        $_url = "http://api.sypexgeo.net/uGNgN/json/$ip"; /// Reg on IT.Roman@YULSUN.RU
        $geo = json_decode($this->request($_url));
        $city = @$geo->city->name_ru;
        $region = @$geo->region->name_ru;
        $ans = [
            'city'=>$city,
            'region'=>$region,
            'geo'=>$geo,
        ];
        return $ans;
    }
    protected function getBrowser()
    {
        $u_agent = Arr::get($_SERVER,'HTTP_USER_AGENT');
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        $ub = '';
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version = @$matches['version'][0];
            }
            else {
                $version = @$matches['version'][1];
            }
        }
        else {
            $version= @$matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'b' => $ub,
            'v' => (int)$version,
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern,
        );
    }

    /** Session start if not started */
    private function isSessionStarted(){
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    public function sessionStart(){
        if ( $this->isSessionStarted() === FALSE ) session_start();
    }

    /**
     * Так как куку нельзя установить после вывода данных
     * То реализован механизм отложенной установки куков
     * Который отработает при следующем запросе
     * Где-то идет вывод данных и не стали тратить время на поски
    */
    public function delayedSetCookie($name,$value)
    {
        if ( $this->isSessionStarted() === FALSE ) session_start();
        $delayedCookies = (array)@$_SESSION['delayedCookies'];
        $delayedCookies[] = [
            'name'=>$name,
            'value'=>$value,
        ];
        $_SESSION['delayedCookies'] = $delayedCookies;
        ///return setcookie( $name, $value, time() - 60 * 60 * 24 * 180, '/', '.yulsun.ru' );
    }

    /** Get Current Route */
    public function getRoute()
    {
        return Route::$routes_array;
    }
    /** Get Current Controller */
    public function getController()
    {
        $route = $this->getRoute();
        return Arr::get($route,'controller');
    }
    /** Get Current Action */
    public function getAction()
    {
        $route = $this->getRoute();
        return Arr::get($route,'action');
    }

    /** Get Current Action
     * @param string $name
     * @return mixed
     */
    public function getParams($name='')
    {
        $route = $this->getRoute();
        $params = Arr::get($route,'params');
        if( $name ) return Arr::get($params,$name);
        return $params;
    }
}
