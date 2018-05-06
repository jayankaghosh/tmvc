<?php

class Router {

    public function route(\Tmvc\Framework\App\Request $request) {

        $route = [
            $request->getModule(),
            "Controller",
            $request->getController(),
            $request->getAction()
        ];

        $className = implode("\\", explode(" ", ucwords(implode(" ", $route))));
        /* @var \Tmvc\Framework\Controller\AbstractController $obj */
        try {
            $obj = new $className();
        } catch (\Exception $exception) {
            throw new \Tmvc\Framework\Exception\EntityNotFoundException("Controller Action Not found");
        }
        if ($obj instanceof \Tmvc\Framework\Controller\AbstractController) {
            $result = $obj->execute($request);

            if ($result instanceof \Tmvc\Framework\View\View) {
                /* @var \Tmvc\Framework\App\Response $response */
                $response = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\App\Response::class);
                $response->setBody($result->render());
            } else if ($result instanceof \Tmvc\Framework\App\Response) {
                $response = $result;
            } else {
                throw new \Tmvc\Framework\Exception\ArgumentMismatchException("Controllers should only return object of type ".\Tmvc\Framework\View\View::class." or ".\Tmvc\Framework\App\Response::class);
            }
            $response->sendResponse();
        } else {
            throw new \Tmvc\Framework\Exception\EntityNotFoundException("Controller Action Not found");
        }
    }
}