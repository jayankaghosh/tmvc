<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\App;


class Response
{

    /**
     * @var int
     */
    private $responseCode = 200;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $body = "";

    /**
     * @var string
     */
    private $redirect = "";

    /**
     * @param string|array $body
     * @return $this
     */
    public function setBody($body) {
        if (is_array($body)) {
            $body = \json_encode($body);
            $this->addHeader('Content-Type', 'application/json');
        }
        $this->body = $body;
        return $this;
    }

    /**
     * @param string$key
     * @param string $value
     * @return $this
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders() {
        return is_array($this->headers) ? $this->headers : [];
    }

    /**
     * @return void
     */
    public function sendResponse() {
        foreach ($this->getHeaders() as $headerKey => $headerValue) {
            header("$headerKey: $headerValue");
        }
        http_response_code($this->getResponseCode());
        if ($this->getRedirect()) {
            header("Location: ".$this->getRedirect());
        } else {
            echo $this->getBody();
        }
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param int $responseCode
     * @return $this
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param string $redirect
     * @return $this
     */
    public function setRedirect(string $redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }
}