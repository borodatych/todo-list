<?php

namespace App\Controllers;

use Core\Config;
use Core\Template;
use App\Helpers\Session;
use App\Helpers\Arr;


class Base extends Template
{
    public $layout  = 'Base';
    public $notices = [];
    public $arrCSS  = [];
    public $arrJS   = [];

    public $title       = 'ToDo List | Demka.Org Team';
    public $keywords    = 'todo list, simple framework';
    public $description = '';
    public $noIndexTag  = FALSE;

    public function __construct()
    {
        parent::__construct();

        /// ЖЕСТКИЙ БАН НА ВРЕМЯ DDOS
        /// $blockIPs = ['127.0.0.2', '127.0.0.3']; /// Смотрим в логи веб сервера
        /// if( in_array($this->ip(),$blockIPs) ) $this->notFound();

        /// This is Ajax ?
        $this->isAjax = $this->ajaxRequest();

        /// This is Bot ?
        $this->isBot = $this->isBot();

        /// Session Start
        $this->session = Session::Init();

        /// Как минимум подключим скрип, который наложит на новый контент нужное
        if ( $this->isAjax ) $this->arrJS[] = '/assets/js/ajax.js?1';

        $this->arrJS[] = '/assets/js/pages/auth.js';
    }
    /**
     * Выполняется после того, как отработают все контроллеры
    */
    public function __destruct()
    {
        /// Уведомления | на потом
        ///$this->notices += Arr::get($this->session,'notices',[],TRUE);
        ///unset($this->session->notices);
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        /// Под условием - подготовка для шаблона/хедера/футера
        if( !$this->isAjax )
        {
            $this->template->arrJS  = $this->arrJS;
            $this->template->arrCSS = $this->arrCSS;

            $this->template->title       = $this->title;
            $this->template->keywords    = $this->keywords;
            $this->template->description = $this->description;

            $this->template->revision = Config::item('revision');
            $this->template->isAuth   = $this->session->isAuth;

            if ( $this->noIndexTag ) $this->template->noIndexTag = '<meta name="robots" content="noindex, nofollow" />';
        }
        else
        {
            /// ТЕОРЕТИЧЕСКИ ТУТ ВООБЩЕ НЕ ДОЛЖНЫ ОКАЗАТЬСЯ
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        parent::__destruct(); /// Команда на рендеринг
    }
}