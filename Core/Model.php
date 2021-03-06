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
        parent::__construct();

        $this->db = new Database(
            Config::$config[Config::$conf]['db']['host'],
            Config::$config[Config::$conf]['db']['user'],
            Config::$config[Config::$conf]['db']['pass'],
            Config::$config[Config::$conf]['db']['base'],
            Config::$config[Config::$conf]['db']['port'],
            Config::$config[Config::$conf]['db']['sock']
        );
    }
    public function __destruct()
    {
        if( $this->db ) $this->db->close();
    }
}
