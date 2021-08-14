<?php


namespace Models;


use Controllers\DatabaseController;
use mysqli;

abstract class Model
{
    protected static function dbConnection(): mysqli
    {
        $db = new DatabaseController();
        return $db->getConnection();
    }
}