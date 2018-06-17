<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Code\Generators;


class MethodGenerator
{
    /**
     * @var string
     */
    private $scope = "";

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var string
     */
    private $body = "\n\n";

    /**
     * @var string[]
     */
    private $comments = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $type
     * @param string $name
     * @param null $default
     * @return $this
     */
    public function addArgument($type, $name, $default = null)
    {
        $this->arguments[] = [
            'type'      =>  $type,
            'name'      =>  "$".$name,
            'default'   =>  $default
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return $this
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
        return $this;
    }


    /**
     * @return string
     */
    public function generate() {
        $content = "";
        $arguments = [];
        foreach ($this->getArguments() as $argument) {
            $argumentDefinition  = $argument['type']." ".$argument['name'];
            if ($argument['default']) {
                $argumentDefinition .= " = ".$argument['default'];
            }
            $arguments[] = trim($argumentDefinition);
        }
        if (count($this->getComments())) {
            $content .= "/**";
            foreach ($this->getComments() as $comment) {
                $content .= "\n * $comment";
            }
            $content .= "\n */\n";
        }
        $content .= $this->getScope()." function ".$this->getName()."(".implode(", ", $arguments).") {";
        $content .= "\n".$this->getBody();
        $content .= "\n}";
        return $content;
    }

    public function __toString()
    {
        return $this->generate();
    }
}