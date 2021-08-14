<?php

require_once __DIR__ . '/Components/Autoloader.php';
$autoloader = new \Components\Autoloader(__DIR__);

use Controllers\IndexController;

$connection = new IndexController();
$connection->sessionStart()->routerInit();

?>

