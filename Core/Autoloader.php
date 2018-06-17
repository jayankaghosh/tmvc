<?php

class Autoloader {

    public function register() {
        spl_autoload_register(function($class) {
            /* Try to load it as a core file first */
            if (!@include_once(str_replace('\\', '/', $class) . '.php')) {
                /* If it is not a core file. Try to load it from the user modules */
                if (!@include_once('app/code/' . str_replace('\\', '/', $class) . '.php')) {
                    /* If it is not a user module. Try to load it from the generated files */
                    if (!@include_once(\Tmvc\Framework\Code\FactoryGenerator::GENERATED_FILE_PATH . str_replace('\\', '/', $class) . '.php')) {
                        throw new \Exception("Couldn't load class: $class");
                    }
                }
            }
        });
    }

}