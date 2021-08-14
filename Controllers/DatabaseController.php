<?php


namespace Controllers;

use mysqli;

class DatabaseController
{
    protected const HOST = 'host';
    protected const PORT = 'port';
    protected const SOCKET = 'socket';
    protected const USER = 'user';
    protected const PASSWORD = 'password';
    protected const DBNAME = 'dbname';

    protected string $host;
    protected string $port;
    protected string $socket;
    protected string $user;
    protected string $password;
    protected string $dbname;

    public function __construct()
    {
        $config = require 'Configs/database.php';

        $this->host=$config[self::HOST];
        $this->port=$config[self::PORT];
        $this->socket=$config[self::SOCKET];
        $this->user=$config[self::USER];
        $this->password=$config[self::PASSWORD];
        $this->dbname=$config[self::DBNAME];
    }

    public function getConnection(): ?mysqli
    {
        $connection = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbname,
            $this->port,
            $this->socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());

        return $connection;
    }


}