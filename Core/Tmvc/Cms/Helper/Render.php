<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Helper;


use Tmvc\Cms\Model\Block;
use Tmvc\Cms\Model\Page;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\View\View;

class Render
{

    const TEMPLATE_VARIABLE_REGEX = "/\{\{@([^{} ]+) ([^ {}]+) ?([^{}]*)\}\}/";

    /**
     * @var Response
     */
    private $response;
    /**
     * @var View
     */
    private $view;

    /**
     * Render constructor.
     * @param Response $response
     * @param View $view
     */
    public function __construct(
        Response $response,
        View $view
    )
    {
        $this->response = $response;
        $this->view = $view;
    }

    /**
     * @param Page $page
     * @return Response
     */
    public function renderPage(Page $page) {
        $body = $this->_parseTemplate($page->getPageContent());
        return $this->response->setBody($body)->setResponseCode($page->getResponseCode());
    }

    /**
     * @param Block $block
     * @return string
     */
    public function renderBlock(Block $block) {
        return $this->_parseTemplate($block->getBlockContent());
    }

    /**
     * @param string $template
     * @return string
     */
    public function parseTemplate($template) {
        return $this->_parseTemplate($template);
    }

    /**
     * @param string $template
     * @return string
     */
    private function _parseTemplate($template) {
        return preg_replace_callback(static::TEMPLATE_VARIABLE_REGEX, [$this, '_replaceVariables'], $template);
    }

    /**
     * @param $args
     * @return string
     * @throws TmvcException
     */
    protected function _replaceVariables($args) {
        @list ($match, $type, $value, $data) = $args;
        switch ($type) {
            case "block":
                /* @var \Tmvc\Cms\Model\Block $block */
                $block = ObjectManager::create(Block::class);
                $block->load($value, "identifier");
                if ($block->getIsEnabled()) {
                    return $this->renderBlock($block);
                } else {
                    return "";
                }
            case "view":
                $block = [];
                foreach (explode(" ", trim($data)) as $datum) {
                    @list($dataKey, $dataValue) = explode("=", $datum);
                    $block[$dataKey] = $dataValue;
                }
                return $this->view->loadView(uniqid(), $value, $block);
            default:
                throw new TmvcException("Error rendering template. Type $type not found");
        }
    }
}