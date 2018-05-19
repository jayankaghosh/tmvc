<?php

class Router {

    protected $initiated = false;

    public function route(\Tmvc\Framework\App\Request $request) {

        try {

            if (
                ($request->getModule() === $request->getController()) &&
                ($request->getController() === $request->getAction()) &&
                ($request->getAction() === "index")
            ) {
                $indexPage = array_pad(explode("/", $this->getAppEnv()->read('default_pages.index')), 3, "index");
                $request
                    ->setModule($indexPage[0])
                    ->setController($indexPage[1])
                    ->setAction($indexPage[2]);
            }

            $route = [
                $request->getModule(),
                "Controller",
                $request->getController(),
                $request->getAction()
            ];

            $className = implode("\\", explode(" ", ucwords(implode(" ", $route))));
            /* @var \Tmvc\Framework\Controller\AbstractController $obj */
            try {
                $obj = \Tmvc\Framework\Tools\ObjectManager::get($className);
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
                    throw new \Tmvc\Framework\Exception\ArgumentMismatchException("Controllers should only return object of type " . \Tmvc\Framework\View\View::class . " or " . \Tmvc\Framework\App\Response::class);
                }
                $response->sendResponse();
            } else {
                throw new \Tmvc\Framework\Exception\EntityNotFoundException("Controller Action Not found");
            }
        } catch (\Tmvc\Framework\Exception\EntityNotFoundException $entityNotFoundException) {
            if (!$this->initiated) {
                $noRoutePage = array_pad(explode("/", $this->getAppEnv()->read('default_pages.404')), 3, "index");
                $this->initiated = true;
                $request
                    ->setModule($noRoutePage[0])
                    ->setController($noRoutePage[1])
                    ->setAction($noRoutePage[2]);
                $this->route($request);
            } else {
                throw $entityNotFoundException;
            }
        }
    }

    /**
     * @return \Tmvc\Framework\Tools\AppEnv
     */
    private function getAppEnv() {
        return \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Tools\AppEnv::class);
    }

}