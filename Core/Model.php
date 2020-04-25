<?php

/**
 * Core\Model
 *
 * This class is our base for handling model classes
 * lets us use the db class directly with models
 * so we won't have to call global $db everytime
 */

namespace Core;


class Model extends Core
{
    /**
     * Model::$db
     *
     * Database instance
     */ 
    public $db = NULL;

    /**
     * Setup the database instance to be used in our
     * model classes.
     */
    public function __construct()
    {
        $this->db = Database::$db;
        ///$this->db->query("SET NAMES 'utf8'");
    }
}