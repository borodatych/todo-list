<?php

/**
 * Core\Route
 *
 * Allowing me to route the way the framework handles urls and
 * also allows you to manipulate the way the app handles the route
 * data.
 */

namespace Core;

class Route
{
    /**
     * Route::static::$route
     *
     * Static array of custom routes set using 
     * Route::set()
     */
    public static $routes = array();
    /**
     * Route::static::$folders
     *
     * Static array of controller folder paths
     * set using Route::registerControllerPath()
     */
    public static $folders = array();
    /**
     * Route::static::$routes_array
     *
     * Static array of the current routes.
     * eg.: controllers, actions, params, sub folder
     */
    public static $routes_array = array(
        'folder'     => null,
        'controller' => null,
        'action'     => null,
        'params'     => array(),
    );
    /**
     * Route::static::$default
     *
     * Default controller/action path (even params if necessary)
     * eg.: controller/action/param/param <- that format
     */
    public static $default = null;
    /**
     * Route::splitPath()
     *
     * This function takes the current url path and then properly
     * format it into an array for later use.  Allowing us to
     * access the correct controllers and controller actions.
     * @return array
     */
    public function splitPath($string = null)
    {
        $request_uri = is_null($string) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : $string;                    /// Get the current path

        $request_uri = preg_replace("/\/+/", '/', $request_uri);                                    /// Make sure we don't have multiple forward slashes after eachother
        $request_uri = preg_replace("/\?.*/i", '', $request_uri);                                   /// Strip _GET data
        $config_url  = preg_replace("/^.*\:\/\//i", '', Config::item('url'));             /// Get the config url
        $request_uri = str_replace($config_url, '', $request_uri);                                              /// Remove config url from current path
        $request_uri = $this->trimSlashes($request_uri);                                                                /// Trim beginning and ending forward slashes
        $path        = @explode("/", $request_uri);                                                            /// Lets explode the current path into an array
        $path_tmp    = array();                                                                                         /// Remove empty array spots

        foreach( $path as $p )
        {
            /// Skip empty strings
            if( empty($p) && $p!=='0' ) continue;
            ///if( empty($p) ) continue;
            $path_tmp[] = $p;
        }
        $path = $path_tmp;

        return empty($path) ? array() : $path;
    }
    /**
     * Route::trimSlashes
     *
     * NOTE: Probably will move this function to a string library
     * This function takes a string and strips forward slashes
     * from the beginning and end of the string.
     * @param string $string
     * @return string
     */
    public function trimSlashes($string)
    {
        /// Remove forward slashes from the beginning and ending of the current path
        $string = preg_replace("/^\/|\/$/i", '', $string);
        return $string;
    }
    public function htmlEntities($string)
    {
        /// Remove forward slashes from the beginning and ending of the current path
        $string = strip_tags($string);
        $string = htmlentities($string, ENT_QUOTES, "UTF-8");
        return $string;
    }
    /**
     * Route::set()
     *
     * This function allows us to set custom routes to certain
     * controllers.  The custom routes can also be regular expressions
     * if $regex is set to true.
     * @param string $find
     * @param string $replace
     * @param boolean $regex - tells the script of regex is enabled
     * @param boolean $gets - влючаем в регулярку поиск по $_SERVER['QUERY_STRING'], по умолчанию не было реализовано
     */
    public static function set($find, $replace, $regex = FALSE, $gets = FALSE)
    {
        self::$routes[] = array($find, $replace, $regex, $gets);
    }
    /**
     * Route::static::setDefault()
     *
     * Sets the default controller/action
     * ex.: Route::setDefault("welcome/home/param/param");
     * @param string $path
     * @return void
     */
    public static function setDefault($path)
    {
        self::$default  = $path;
    }
    /**
     * Route::registerDefault()
     *
     * Basically sets up the default path then
     * tells the route which controller/action the route
     * class should be calling.
     * @return void
     */
    public function registerDefault()
    {
        if( !is_null(self::$default) )
        {
            $default_path = $this->splitPath(self::$default);
            /// Set default path
            $this->setPath($default_path);
        }
    }
    /**
     * Route::registerControllerPath()
     *
     * This function would allow you to ask the framework to look
     * for this folder instead of the root controller folder when
     * being called from the url.  Allowing you to have sub folders
     * and possibly unlimited sub folders where you call your
     * controller classes from.
     * @return void
     */
    public static function registerControllerPath($path)
    {
        self::$folders[] = $path;
    }
    /**
     * Route::init()
     *
     * This function puts everything together and makes the world
     * go round.
     * @return void
     */
    public function init()
    {
        $current_path = $this->splitPath(); /// Get the current path
        $this->setPath($current_path);      /// Getup the controller properly
        $this->reRoute($current_path);      /// Start rewrouting
        $this->loadController();            /// Lets try loading the current controller if exists
    }
    /**
     * Route::setPath()
     *
     * This function basically takes a path and split it into an array
     * and it also checks if the current route matches any folders set.
     * By folders I mean sub folders in the controller folder.  Allowing
     * us to access controllers in sub folders properly.
     * @param string $current_path
     * @return array
     */
    public function setPath($current_path)
    {
        $current_folder = [];
        /// Lets make sure the curreht path isn't empty
        if( count($current_path) > 0 )
        {
            /// Lets try to get the correct controller folder
            $folders = array();
            foreach( self::$folders as $folder ) $folders[] = $this->trimSlashes($folder);
            /// Loop through the folders array to check if the current path starts with this
            foreach( $folders as $folder )
            {
                if( $folder == @$current_path[0] )
                {
                    /// Are we in a sub folder?
                    $current_folder[] = ucfirst(strtolower($folder));
                    /// Remove the first item in the array
                    array_shift($current_path);
                    ///break;
                }
            }
            /// Setup routes array
            if( !empty($current_folder) ) self::$routes_array['folder'] = $current_folder;

            if( !empty($current_path) )
            {
                self::$routes_array['controller'] = current($current_path);
                array_shift($current_path);
            }
            if( !empty($current_path) )
            {
                self::$routes_array['action'] = current($current_path);
                array_shift($current_path);
            }
            if( !empty($current_path) )
            {
                $params = array();
                foreach( $current_path as $_path )
                {
                    $_path = urldecode($_path);
                    $_path = $this->htmlEntities($_path);
                    $_path = urlencode($_path);
                    $params[] = $_path;
                }
                self::$routes_array['params'] = $params;
            }
        }
        else
        {
            /// Lets attempt to set the default controller
            $this->registerDefault();
        }
    }
    /**
     * Route::reRoute() 
     *
     * This will attempt to set a new route based on the 
     * routes array.  This allows us to override controllers
     * actions to new controller/actions.  So even if a controller
     * doesn't exists we could route it to a different CONTROLLER.
     */
    public function reRoute($current_path)
    {
        ///$current_path  = $this->splitPath(); /// Вот нахуя это здесь не понятно, можно же передать!
        $current_path_str = implode('/', $current_path);

        /// Loop through the user set routes
        foreach( self::$routes as $route )
        {
            /// If with gets
            if( isset($route[3]) && $route[3] == true ) $workString = $current_path_str.'?'.$_SERVER['QUERY_STRING'];
            else $workString = $current_path_str;

            /// Check if we're using regular expressions or not
            $regex = isset($route[2]) && $route[2] == true ? $route[0] : "^".preg_quote($route[0], '/')."$";

            /// Check if there was any matches of the current route
            if( preg_match('/' . $regex . '/', $workString, $matches) )
            {
                // There was only one match which had no params, so lets remove all the $ signs
                if( count($matches) == 1 )
                {
                    /// NOTE: debating if I should keep the $1 variables in the string or not, will ask around.
                    $route[1] = preg_replace('/\$\d+/', '', $route[1]);
                }
                else
                {
                    foreach( $matches as $id => $match )
                    {
                        if( $id == 0 ) continue;
                        $route[1] = str_replace('$' . $id, $match, $route[1]);
                    }
                }
                /// Set the new path (overwriting any controllers to this new controller)
                $new_path = $this->splitPath($route[1]);
                $this->setPath($new_path);
            }
        }
    }

    public static function NotFound($view=NULL)
    {
        header("HTTP/1.0 404 Not Found");
        if( !$view ) $view = ( $_v = Config::item('404') ) ? $_v : '404';
        $error404 = new View("Errors/$view");
        $error404->render(true);
    }
    public static function AccessDenied($view=NULL)
    {
        header("HTTP/1.0 401 Unauthorized");
        if( !$view ) $view = ( $_v = Config::item('401') ) ? $_v : '401';
        $error404 = new View("Errors/$view");
        $error404->render(true);
    }

    /**
     * Route::loadController()
     *
     * Lets attempt to load the current controller for the
     * current page loaded.  If the controller exists, but
     * the controller action doesn't exists lets attempt to load
     * the default action.  If that doesn't exists lets throw a 404
     * page not found error.
     * @throws \Exception
     */
    public function loadController()
    {
        /// Lets try loading the classes
        $routes       = self::$routes_array;
        $class_folder = empty($routes['folder']) ? null : implode('\\',$routes['folder']) . '\\';
        $controller   = 'App\\Controllers\\' . $class_folder . ucfirst(strtolower($routes['controller']));
        $method       = 'action_' . $routes['action'];
        $params       = $routes['params'];

        /// Если вдруг запутались, то тут можно подглядеть куда завернулся маршрут
        /// print'<pre>';print_r([$controller,$class_folder,$routes]);print'</pre>';exit;

        /// Check if the class exists
        if( class_exists($controller) )
        {
            $class = new $controller();

            if( method_exists($class, $method) )
            {
                call_user_func_array(array($class, $method), $params);
            }
            else
            {
                if( method_exists($class, 'action_index') ) call_user_func(array($class, 'action_index'));
                else throw new \Exception("Couldn't load the required controller method '{$controller}::action_index'.");
            }
        }
        else
        {
            /// Если нужно сделать 302 редирект со старого сайта
            /// require_once ROOT.'/App/Libraries/rewrite.php';
            static::NotFound();
        }
    }
    
    public function __construct() {}
}