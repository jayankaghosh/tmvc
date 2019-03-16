<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Sections\Page;


use Tmvc\Backend\Block\Backend\Sections\AbstractSection;
use Tmvc\Backend\Block\Backend\Sections\Context;
use Tmvc\Cms\Model\Page\Source\ResponseCode;
use Tmvc\Framework\App\Request;
use Tmvc\Cms\Model\PageFactory;
use Tmvc\Framework\Exception\TmvcException;

class Form extends AbstractSection
{
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResponseCode
     */
    private $responseCodeSource;

    /**
     * Form constructor.
     * @param Context $context
     * @param Request $request
     * @param PageFactory $pageFactory
     * @param ResponseCode $responseCodeSource
     * @param $currentSection
     */
    public function __construct(
        Context $context,
        Request $request,
        PageFactory $pageFactory,
        ResponseCode $responseCodeSource,
        $currentSection
    )
    {
        parent::__construct($context, $currentSection);
        $this->request = $request;
        $this->pageFactory = $pageFactory;
        $this->responseCodeSource = $responseCodeSource;
    }

    /**
     * @return \Tmvc\Cms\Model\Page
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    protected function getDataSource()
    {
        $page = $this->pageFactory->create();
        if ($this->getId()){
            $page->load($this->getId());
            if (!$page->getId()) {
                throw new TmvcException(sprintf("Page with ID %s does not exist", $this->getId()));
            }
        }
        return $page;
    }

    protected function getId() {
        return $this->request->getParam('id');
    }

    /**
     * @return \Tmvc\Framework\View\View
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function loadWidget()
    {
        $dataSource = $this->getDataSource();
        return $this->getContext()
            ->getFormWidgetFactory()
            ->create([
                'dataSource'    =>  $dataSource
            ])
            ->setActionService('cms_page_save')
            ->addField(
                'id',
                '',
                \Tmvc\Ui\Block\Widget\Form::FIELD_TYPE_HIDDEN,
                [],
                $this->getId()
            )->addField(
                'is_enabled',
                'Is Active',
                \Tmvc\Ui\Block\Widget\Form::FIELD_TYPE_CHECKBOX
            )->addField(
                'identifier',
                'Identifier',
                \Tmvc\Ui\Block\Widget\Form::FIELD_TYPE_TEXT
            )->addField(
                'response_code',
                'Response Code',
                \Tmvc\Ui\Block\Widget\Form::FIELD_TYPE_SELECT,
                [
                    'options'   =>  $this->responseCodeSource->toOptionArray()
                ]
            )->addField(
                'page_content',
                'Page Content',
                \Tmvc\Ui\Block\Widget\Form::FIELD_TYPE_TEXTAREA
            )->setTitle(
                $dataSource->getId() ? sprintf("Edit Page \"%s\"", $dataSource->getData('identifier')) : "Add New Page"
            )->render();
    }
}