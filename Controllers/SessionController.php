<?php


namespace Controllers;


class SessionController
{
    public static function sessionStart(): void
    {
        session_start();
    }

    function sessionSet(string $key, $data) {
        $_SESSION[$key] = $data;
    }

    function sessionGet (string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    function setFlash(string $key, $data): void
    {
        $_SESSION['flash'][$key] = $data;
    }

    function getFlash(string $key, $default = null) {
        if (isset($_SESSION['flash'][$key])) {
            $data = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
        } else {
            $data = $default;
        }
        return $data;
    }

    public static function sessionUnset():void
    {
        session_unset();
    }

}