<?php


namespace Controllers;


use Components\Router;

class IndexController
{

    public function databaseConnection(): IndexController
    {
        $db = new DatabaseController();
        $db->getConnection();
        return $this;
    }

    public function sessionStart(): IndexController
    {
        $session = new SessionController();
        $session->sessionStart();
        return $this;
    }

    public function routerInit(): IndexController
    {
        $router = new Router();
        $router->run();
        return $this;
    }

}