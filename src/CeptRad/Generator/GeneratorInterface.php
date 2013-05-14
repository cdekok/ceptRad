<?php
namespace CeptRad\Generator;

interface GeneratorInterface
{
    /**
     * Event triggered after generating code object
     */
    const EVENT_POST_GENERATE = 'CeptRad\Generator::EVENT_POST_GENERATE';

    /**
     * Event triggered after writing the code to file
     */
    const EVENT_POST_WRITE = 'CeptRad\Generator::EVENT_POST_WRITE';

    /**
     * Write code to file
     *
     * @return void
     */
    public function write($file);
}
