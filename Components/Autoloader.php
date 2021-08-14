<?php


namespace Components;




class Autoloader
{
    private string $baseRout;

    public function __construct(string $baseRout)
    {
        $this->baseRout = rtrim($baseRout, DIRECTORY_SEPARATOR);
        spl_autoload_register(array($this, 'load'));
    }

    public function load($class)
    {
        $rout =$this->baseRout . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (!file_exists($rout)) {
            throw new \Exception("Class {$class} can not be loaded");
        }

        require_once $rout;
    }
}