<?php

/**
 * Example Controller
 */

namespace App\Controllers\Room;


class Dashboard extends Base
{
    
    public function __construct(){ parent::__construct(); }

    public function action_index($region=0)
    {
        $values = array(
            'img' => 'http://2.bp.blogspot.com/-e8GMCdKgBP0/TZtUSMT8ZLI/AAAAAAAABHk/9DBV4p1LSaE/s1600/404+fattie.jpg'
        );
        $content = $this->partial('img',$values);

        $this->template->content = $content;
    }
}