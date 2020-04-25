<?php

/**
 * Core\Exception
 *
 * Simple Exception that we'll add more functionality to soon enough.
 */

namespace Core;


class Exception extends \Exception
{

    /**
     * Exception::__construct()
     *
     * Calls the Exception construct
     */
    public function __construct($err)
    {
        parent::__construct($err);
    }

}