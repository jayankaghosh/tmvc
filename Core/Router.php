<?php

class Router {

    public function route($path) {

        $route = [
            $path['module'],
            "Controller",
            $path['controller'],
            $path['action']
        ];

        $className = implode("\\", explode(" ", ucwords(implode(" ", $route))));
        /* @var \Tmvc\Framework\Controller\AbstractController $obj */
        $obj = new $className();
        if ($obj instanceof \Tmvc\Framework\Controller\AbstractController) {
            $obj->execute();
        } else {
            throw new \Tmvc\Framework\Exception\TmvcException("Controller Action Not found");
        }
    }
}