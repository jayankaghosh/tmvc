<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework;


use Tmvc\Framework\Exception\TmvcException;

class DataObject implements \JsonSerializable
{

    protected $data = [];

    /**
     * @param string|array $key
     * @param mixed|null $value
     * @return $this
     */
    public function setData($key, $value = null) {
        if (is_array($key)) {
            $this->data = $key;
        } else {
            $this->addData($key, $value);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $elegant
     * @return $this
     * @throws TmvcException
     */
    public function addData($key, $value, $elegant = true) {
        if (!$elegant && isset($this->data[$key])) {
            throw new TmvcException("$key already exists in object");
        }
        @$this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param bool $elegant
     * @return $this
     * @throws TmvcException
     */
    public function unsetData($key, $elegant = true) {
        if (!isset($this->data[$key])) {
            if (!$elegant) {
                throw new TmvcException("$key not found in object");
            }
        } else {
            unset($this->data[$key]);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return array|mixed
     */
    public function getData($key = null, $default = null) {
        if (!$key) {
            return $this->data;
        } else {
            return isset($this->data[$key]) ? $this->data[$key] : $default;
        }
    }

    public function __call($name, $arguments) {
        $type = substr($name, 0, 3);
        $key = $this->camelToSnakeCase(substr($name, 3));
        switch ($type) {
            case "get":
                return $this->getData($key);
                break;
            case "set":
                return $this->setData($key, $arguments[0]);
            case "uns":
                return $this->unsetData($key);
        }
    }

    /**
     * @param string $string
     * @return string
     */
    public function snakeToCamelCase($string = "")
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    public function camelToSnakeCase($string = "") {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}