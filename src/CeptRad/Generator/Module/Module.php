<?php
namespace CeptRad\Generator\Module;

/**
 * Create module skeleton
 */
class Module {
    
    protected $dirStructure = [
        'src',
        'view',
        'language',
        'config',               
    ];
    
    /**
     * Create module skeleton
     * 
     * @param string $moduleName
     * @param string $path
     */
    public function create($moduleName, $path)
    {
        $modulePath = $path.'/'.$moduleName;
        $this->mkdir($modulePath);
        foreach ($this->dirStructure as $dir) {
            $this->mkdir($modulePath.'/'.$dir);
            if ($dir === 'view') {
                $this->mkdir($modulePath.'/'.$dir.'/'.  strtolower((new \Zend\Filter\Word\CamelCaseToDash())->filter($moduleName)));
            }
        }
        
        // Write Module.php
        $module = new \CeptRad\Generator\Module\Files\Module($moduleName);
        $module->write($modulePath.'/Module.php');
        
        // Write module.config.php
        $config = new \CeptRad\Generator\Module\Files\Config;
        $config->write($modulePath.'/config/module.config.php');
    }

    /**
     * 
     * @param string $dir
     * @throws \CeptRad\Generator\Module\Exception\MkdirException
     */
    private function mkdir($dir)
    {
        
        if (is_dir($dir)) {
            return;
        }
        if (!mkdir($dir)) {
            echo realpath($dir);
            throw new \CeptRad\Generator\Module\Exception\MkdirException('Could not create dir: '. $dir);
        }
    }
}