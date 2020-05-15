<?php

/**
 * User Controller
 */

namespace App\Controllers;

use App\Helpers\Arr;
use App\Helpers\Session;

class User extends Base
{
    public function action_login()
    {
        $status = 0;

        $md5 = $check = NULL;
        if( $this->isFormSubmitted() )
        {
            $login = $this->receive('login');
            $password = $this->receive('password');

            /// admin 123
            $md5 = '0192023a7bbd73250516f069df18b500';
            $check = md5($login.$password);

            $status = (int) ( $md5 === $check );
        }

        $this->session->isAuth = $status;
        $this->jEcho([
            'status' => $status,
            ///'_need' => $md5,
            ///'_have' => $check,
            ///'$_POST' => $_POST,
            ///'$session' => $this->session->fetch(),
        ]);
    }
    public function action_logout()
    {
        $this->isAjax = TRUE; /// fetch голову кружит, принудительно отдаем json
        $this->session->isAuth = 0;
        $this->jEcho(['status' => 1]);
    }
}
