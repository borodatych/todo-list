<?php

/**
 * Session helper class. Provides additional formatting methods that for working with numbers.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;

class Session extends \Core\Session
{
    protected function __construct()
    {
        parent::__construct();
    }

    public function deactivate()
    {
        session_unset();
    }
    public function destroy()
    {
        session_destroy();
    }
}