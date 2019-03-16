<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Model\Backend\Service;


use Tmvc\Backend\Service\ServiceInterface;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\Url;
use Tmvc\Cms\Model\PageFactory;
use Tmvc\Ui\Model\Message\Manager as MessageManager;

class CmsPageDelete implements ServiceInterface
{
    /**
     * @var Url
     */
    private $url;
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * CmsPageSave constructor.
     * @param Url $url
     * @param PageFactory $pageFactory
     * @param MessageManager $messageManager
     */
    public function __construct(
        Url $url,
        PageFactory $pageFactory,
        MessageManager $messageManager
    )
    {
        $this->url = $url;
        $this->pageFactory = $pageFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws TmvcException
     */
    public function execute(Request $request, Response $response)
    {
        try {
            $id = $request->getParam('id');
            if (!$id) {
                throw new TmvcException("ID is a required field");
            }
            $page = $this->pageFactory->create()->load($id);
            if (!$page->getId()) {
                throw new TmvcException(sprintf("CMS Page with ID %s does not exist", $id));
            }
            $page->delete();
            $this->messageManager->addMessage("Page deleted successfully", MessageManager::TYPE_SUCCESS);
        } catch (TmvcException $tmvcException) {
            $this->messageManager->addMessage($tmvcException->getMessage(), MessageManager::TYPE_ERROR);
        } catch (\Exception $exception) {
            $this->messageManager->addMessage("Could not delete page", MessageManager::TYPE_ERROR);
        }

        return $response->setRedirect($this->url->getUrl('backend/index/index', ['section' => 'cms-page-listing']));
    }
}