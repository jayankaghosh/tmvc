<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Code;


use Tmvc\Framework\Code\Generators\ClassGenerator;
use Tmvc\Framework\Tools\File;
use Tmvc\Framework\Tools\ObjectManager;

class FactoryGenerator
{

    const GENERATED_FILE_PATH = "var/generated/";

    /**
     * @var ClassGenerator
     */
    private $classGenerator;
    /**
     * @var File
     */
    private $file;

    /**
     * FactoryGenerator constructor.
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __construct()
    {
        $this->classGenerator = new ClassGenerator();
        $this->file = new File();
    }

    /**
     * @return ClassGenerator
     */
    public function getClassGenerator() {
        return $this->classGenerator;
    }

    public function generate($className) {
        $factory = $this->classGenerator
            ->setName($className."Factory")
            ->addComment("Use this class to create a new instance of the \\$className class")
            ->addMethod("create", [
                [
                    "type" => "array",
                    "name" => "data",
                    "default" => "[]"
                ]
            ], 'return \\'.ObjectManager::class.'::create(\\'.$className.'::class, $data);',
                "public",
                [
                    "@param array \$data",
                    "@return \\$className"
                ]);
        $this->file->load(static::GENERATED_FILE_PATH.str_replace("\\", "/", $className)."Factory.php")->write($factory);
    }

}