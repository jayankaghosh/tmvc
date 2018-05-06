<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\App;


class Request
{
    /**
     * Request Methods
     */
    const METHOD_GET = "GET";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_UPDATE = "UPDATE";
    const METHOD_DELETE = "DELETE";

    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var mixed[]
     */
    private $params;

    /**
     * @var mixed[]
     */
    private $postParams;

    /**
     * @var string
     */
    private $method;

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getParam($name, $default = null) {
        return isset($this->getParams()[$name]) ? $this->getParams()[$name] : $default;
    }

    /**
     * @param mixed[] $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    public function getPostParam($name, $default) {
        return isset($this->getPostParams()[$name]) ? $this->getPostParams()[$name] : $default;
    }

    /**
     * @param mixed[] $postParams
     * @return $this
     */
    public function setPostParams($postParams)
    {
        $this->postParams = $postParams;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
}