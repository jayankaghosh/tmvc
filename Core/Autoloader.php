<?php

use Tmvc\Framework\Code\FactoryGenerator;

class Autoloader {

    public function register() {
        spl_autoload_register(function($class) {
            $path = str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $class) . '.php';
            /* Try to load it as a core file first */
            if (!@include_once($path)) {
                /* If it is not a core file. Try to load it from the user modules */
                if (!@include_once('app/code/' . $path)) {
                    /* If it is not a user module. Try to load it from the generated files */
                    if (!@include_once(FactoryGenerator::GENERATED_FILE_PATH . $path)) {
                        throw new \Exception("Couldn't load class: $class");
                    }
                }
            }
        });
    }

}