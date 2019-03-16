<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Command\User;


use Tmvc\Backend\Model\AdminFactory;
use Tmvc\Backend\Model\AdminManagement;
use Tmvc\Eav\Model\Eav\AttributeLoader;
use Tmvc\Framework\Cli\AbstractCommand;
use Tmvc\Framework\Cli\Request;
use Tmvc\Framework\Cli\Request\OptionFactory;
use Tmvc\Framework\Cli\Response;
use Tmvc\Framework\Exception\TmvcException;

class Create extends AbstractCommand
{
    /**
     * @var AdminFactory
     */
    private $adminFactory;
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;
    /**
     * @var AdminManagement
     */
    private $adminManagement;

    /**
     * Create constructor.
     * @param OptionFactory $optionFactory
     * @param AdminFactory $adminFactory
     * @param AttributeLoader $attributeLoader
     * @param AdminManagement $adminManagement
     */
    public function __construct(
        OptionFactory $optionFactory,
        AdminFactory $adminFactory,
        AttributeLoader $attributeLoader,
        AdminManagement $adminManagement
    )
    {
        parent::__construct($optionFactory);
        $this->adminFactory = $adminFactory;
        $this->attributeLoader = $attributeLoader;
        $this->adminManagement = $adminManagement;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function run(Request $request, Response $response)
    {
        $data = $request->getOptions(['username', 'password']);
        try {
            $this->adminManagement->create($request->getOption('username'), $request->getOption('password'), $data);
            $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)->writeln("Admin user created");
        } catch (TmvcException $tmvcException) {
            $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_RED)->writeln($tmvcException->getMessage());
        }
    }

    public function getTitle()
    {
        return "Create a new Admin User";
    }

    public function getDescription()
    {
        return "Create a new Admin User using email and password";
    }

    public function getOptions()
    {
        $fields = [
            $this->getNewOption('username', true),
            $this->getNewOption('password', true)
        ];

        $additionalAttributes = $this->attributeLoader->getAllAttributes($this->adminFactory->create(), false);

        foreach ($additionalAttributes as $additionalAttribute) {
            $fields[] = $this->getNewOption($additionalAttribute->getData('attribute_code'), false);
        }

        return $fields;
    }
}