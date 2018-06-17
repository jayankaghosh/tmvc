<?php

    define("PROJECT_ROOT_PATH", __DIR__."/");
    require_once 'Core/Application.php';
    $app = new Application($_SERVER);
    $app->run();