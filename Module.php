<?php
namespace CeptRad;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleBannerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleBanner(AdapterInterface $console)
    {
        $figlet = new \Zend\Text\Figlet\Figlet();
        return $figlet->render('RAD');
    }

    public function getConsoleUsage(Console $console) {
        return array(
            // Commands
            'rad form <module>'    => 'Generate form\'s based on database tables',
            // Parameters
            array(
                'module', 'The module name where the form\'s will be generated',
                '--schema=', 'The database schema to generate the forms from',
                '--table=', 'Single table to generate form from'
            ),
        );
    }
}
