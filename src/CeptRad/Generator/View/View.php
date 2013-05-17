<?php
namespace CeptRad\Generator\View;

use CeptRad\Generator\AbstractGenerator;

class View extends AbstractGenerator
{

    /**
     * Generate view
     *
     * @return string
     */
    public function generate($body)
    {
        if (!$body) {

        }

        $this->setFile($body);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }
}
