<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Model\Message;


use Tmvc\Framework\Session\AbstractSession;

class Manager extends AbstractSession
{

    const TYPE_SUCCESS = "success";
    const TYPE_ERROR = "error";
    const TYPE_WARNING = "warning";
    const TYPE_INFO = "info";

    public function getSessionName()
    {
        return "Tmvc_Ui/messages";
    }

    /**
     * @return array
     */
    public function getMessages() {
        $messages = $this->getData('messages');
        return is_array($messages) ? $messages : [];
    }

    public function flushMessages() {
        return $this->setData('messages', []);
    }

    /**
     * @param string $message
     * @param string $type
     * @return Manager
     */
    public function addMessage($message, $type = self::TYPE_INFO) {
        $messages = $this->getMessages();
        $messages[] = [
            'type'      =>  $type,
            'message'   =>  (string)$message
        ];
        return $this->setData('messages', $messages);
    }
}