<?php

    define("PROJECT_ROOT_PATH", __DIR__."/");
    define("ARGV", isset($argv) ? $argv : []);
    require_once 'Core/Application.php';
    $app = new Application();
    $app->run();