<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Block\Messages;


use Tmvc\Framework\DataObject;
use Tmvc\Ui\Model\Message\Manager as MessageManager;

class Init extends DataObject
{
    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var string
     */
    private $messages = null;

    /**
     * Init constructor.
     * @param MessageManager $messageManager
     */
    public function __construct(
        MessageManager $messageManager
    )
    {
        $this->messageManager = $messageManager;
    }

    public function getMessages() {
        if (!$this->messages) {
            $this->messages = \json_encode($this->messageManager->getMessages());
            $this->messageManager->flushMessages();
        }
        return $this->messages;
    }
}