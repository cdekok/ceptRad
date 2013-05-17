<?php
namespace CeptRad\Generator\Config;

use CeptRad\Generator\AbstractGenerator;

class Config extends AbstractGenerator
{

    /**
     * Generate array config file
     *
     * @param array $config
     * @return type
     */
    public function generate(array $config)
    {
        $value = new \Zend\Code\Generator\ValueGenerator($config);
        $file = new \Zend\Code\Generator\FileGenerator();
        $file->setBody($value);
        $this->setFile($file);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }
}
