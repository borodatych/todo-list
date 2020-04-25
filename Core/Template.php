<?php

/**
 * View\Template
 *
 * This class allows you to use the view class to wrap
 * other templates with a body template.  So say you have
 * similar body templates for a project and the only thing that
 * changes is the content.  Why load the header/footer every time?
 * That's because you shouldn't have to!
 */

namespace Core;

use App\Helpers\Arr;

class Template extends Controller
{
    public $isAjax = FALSE; /// Если TRUE то спасаемся от рендеринга всего шаблона по наследованию
    public $isBot = FALSE;
    public $session = NULL;

    /**
     * Template::$layout
     *
     * This is the default template file that you wrap around
     * other view files.
     */
    public $layout = 'body';

    /**
     * Template::$template
     *
     * This is an instance of the \Core\View class
     * and you can assign variables to the view object
     * like you would normally in your controllers.
     * eg.: $this->template->content, then you would edit
     * the App/View/body.php file or w/e default template file
     * you have set.
     */
    public $template = null;

    /**
     * View::__construct()
     *
     * You want to setup an instance of the view class then
     * force them to call the parent::__construct() from the
     * controller.
     */
    public function __construct()
    {
        parent::__construct();
        /// Assign template view object to the template variable
       /// print'<pre>';print_r("QWE! ".$this->layout);print'</pre>';
        $this->template = new View('Layouts/'.$this->layout);
    }

    /**
     * View::__destruct()
     *
     * Once everything is done executing output the data to the
     * page for viewing.
     */
    public function __destruct()
    {
        if( !$this->isAjax )
        {
            $this->template->render(true);
        }
    }

    /**
     * Controller::loadHelper()
     *
     * This function allows us to access helper classes
     * within the view class files when loaded. To access
     * the helper class you would do: $helper->classname->function()
     * inside the view file.
     * @param string $helper
     * @return void
     */
    public function loadHelper($helper)
    {
        View::setHelper($helper);
    }

    public function layout($values=array())
    {
        ///
    }
    /// Cloned From Partial - служит для рендеринга кусочков HTML
    public static function piece($partial=NULL,$values=array(),$inPage=TRUE)
    {
        $_route = Route::$routes_array;
        if( $partial && !$inPage ) $pathView = $partial;
        else
        {
            if( !$partial ) $partial = Arr::get($_route,'action');
            $pathView = "Pages";
            $pathView .= ($r=Arr::get($_route,'folder')) ? "/".implode('/',$r) :'';
            $pathView .= ($r=Arr::get($_route,'controller')) ? "/".ucfirst($r) :'';
            $pathView .= "/".ucfirst($partial);
        }

        $view = new View($pathView);
        foreach( $values AS $name=>$value ) $view->{$name} = $value;

        $view = $view->render();
        return $view;
    }
    /// Рендеринг основной страницы
    public function partial($partial=NULL,$values=array(),$inPage=TRUE)
    {
        $_route = Route::$routes_array;
        if( $partial && !$inPage ) $pathView = $partial;
        else
        {
            if( !$partial ) $partial = Arr::get($_route,'action');
            $pathView = "Pages";
            $pathView .= ($r=Arr::get($_route,'folder')) ? "/".implode('/',$r) :'';
            $pathView .= ($r=Arr::get($_route,'controller')) ? "/".ucfirst($r) :'';
            $pathView .= "/".ucfirst($partial);
        }

        $view = new View($pathView);
        foreach( $values AS $name=>$value ) $view->{$name} = $value;
        $view->session = $this->session;
        $view->isAjax = $this->isAjax;
        $view->isBot = $this->isBot;
        $view = $view->render();
        return $view;
    }
    public function notFound()
    {
        $this->isAjax = TRUE;
        Route::NotFound();
        exit;
    }
    /// Закрываем, иначе все не найденные методы будут через index проходить
    final function action_index() { $this->notFound(); }
}