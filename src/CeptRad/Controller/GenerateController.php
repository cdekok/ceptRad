<?php
namespace CeptRad\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

class GenerateController extends AbstractActionController
{
    public function formAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $module = $request->getParam('module');
        $db = $this->getServiceLocator()->get('db');
        return 'Do something'.PHP_EOL;
    }
}
