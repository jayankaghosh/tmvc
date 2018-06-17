<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Code\Generators;


use Tmvc\Framework\Tools\ObjectManager;

class ClassGenerator
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $parent = "";

    /**
     * @var MethodGenerator[]
     */
    private $methods = [];

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
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     * @return $this
     */
    public function setParent(string $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return MethodGenerator[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @param string $body
     * @param string $scope
     * @param array $comments
     * @return $this
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function addMethod($name, array $arguments, $body, $scope = "public", $comments = [])
    {
        /* @var \Tmvc\Framework\Code\Generators\MethodGenerator $methodGenerator */
        $methodGenerator = ObjectManager::create(MethodGenerator::class);
        $methodGenerator->setScope($scope)->setName($name)->setBody($body);
        foreach ($arguments as $argument) {
            $methodGenerator->addArgument($argument['type'], $argument['name'], $argument['default']);
        }
        foreach ($comments as $comment) {
            $methodGenerator->addComment($comment);
        }
        $this->methods[] = $methodGenerator;
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
        $classNameWithNamespace = explode("\\", $this->getName());
        $className = array_pop($classNameWithNamespace);
        $namespace = implode("\\", $classNameWithNamespace);

        $content = "<?php\n\n";
        $content .= "namespace $namespace;\n\n";
        if (count($this->getComments())) {
            $content .= "/**";
            foreach ($this->getComments() as $comment) {
                $content .= "\n * $comment";
            }
            $content .= "\n */\n";
        }
        $content .= "class $className";
        if ($this->getParent()) {
            $content .= " extends ".$this->getParent();
        }
        $content .= " {\n";
        foreach ($this->getMethods() as $method) {
            $content .= "\n".$method."\n";
        }
        $content .= "\n}";
        return $content;
    }

    public function __toString()
    {
        return $this->generate();
    }

}