<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Controller;


use Tmvc\Backend\Model\Session;
use Tmvc\Framework\Controller\AbstractController;
use Tmvc\Framework\Controller\Context;

abstract class AbstractAction extends AbstractController
{
    /**
     * @var Session
     */
    private $session;

    /**
     * AbstractAction constructor.
     * @param Context $context
     * @param Session $session
     */
    public function __construct(
        Context $context,
        Session $session
    )
    {
        parent::__construct($context);
        $this->session = $session;
    }
}