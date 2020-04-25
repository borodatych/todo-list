<?php

/**
 * Room\Base Controller
 */

namespace App\Controllers\Room;

use \Core\Template;

class Base extends Template
{
    public $layout = 'Base';

    public function __construct()
    {
        parent::__construct();
    }
    public function __destruct()
    {
        parent::__destruct();
    }
}