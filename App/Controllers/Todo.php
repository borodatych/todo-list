<?php

/**
 * Todo Controller
 */

namespace App\Controllers;

use App\Helpers\Arr;
use App\Models\Task;
use Core\Database;

class Todo extends Access
{
    private $mTask;
    public function __construct()
    {
        parent::__construct();
        $this->mTask = new Task();
    }

    public function action_list()
    {
        /// Подключаем скрипты для это страницы
        /// $this->arrCSS[] = '';
        $this->arrJS[] = '/assets/js/pages/todo/datatable.js?2';
        $this->arrJS[] = '/assets/js/pages/todo/form.js?1';

        $this->template->content = $this->partial('Sections',[]);
    }
    public function action_load()
    {
        $params = [];
        $res = $this->mTask->all($params);

        $status = Arr::get($res,'status');
        $items = Arr::get($res,'items');

        $this->jEcho([
            'status' => $status,
            'items' => $items,
            /// Подглядеть, что там плагин на сервер шлет
            ///'$_POST' => $_POST,
            ///'$session' => $this->session->fetch(),
        ]);
    }
    public function action_get()
    {
        $id = $this->rcv('id');
        $res = $this->mTask->get($id);

        $status = Arr::get($res,'status');
        $item = Arr::get($res,'item');

        $this->jEcho([
            'status' => $status,
            'item' => $item,
        ]);
    }

    public function action_add()
    {
        $name = $this->rcv('name');
        $email = $this->rcv('email');
        $note = $this->rcv('note');

        $res = $this->mTask->add($name,$email,$note);

        $status = Arr::get($res,'status');
        $message = $status > 0 ? 'Успешно !' : Database::$db->error;

        $this->jEcho([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function action_upd()
    {
        if( !$this->session->isAuth ) $this->notFound();

        $id = $this->rcv('id');
        $name = $this->rcv('name');
        $email = $this->rcv('email');
        $note = $this->rcv('note');
        $completed = (int)$this->rcv('completed');

        $res = $this->mTask->upd($id, [
            'name' => $name,
            'email' => $email,
            'note' => $note,
            'completed' => $completed,
        ]);

        $status = Arr::get($res,'status');
        $message = $status > 0 ? 'Успешно !' : Database::$db->error;

        $this->jEcho([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function action_del()
    {
        $this->isAjax = TRUE;
        if( !$this->session->isAuth ) $this->notFound();


        $id = $this->rcv('id');

        $res = $this->mTask->del($id);

        $status = Arr::get($res,'status');
        $message = $status > 0 ? 'Успешно !' : Database::$db->error;

        $this->jEcho([
            'status' => $status,
            'message' => $message,
        ]);
    }
}