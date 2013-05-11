<?php
namespace CeptRad\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

abstract class AbstractConsoleController extends AbstractActionController
{
    /**
     * Set event manager
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function setEventManager(\Zend\EventManager\EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', array($this, 'checkRequest'), 10);
    }

    /**
     * Check if this is a console request
     *
     * @param EventManagerInterface $e
     * @throws \RuntimeException
     * @return void
     */
    public function checkRequest(\Zend\Mvc\MvcEvent $e)
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }
    }
}
