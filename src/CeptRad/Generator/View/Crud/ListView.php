<?php
namespace CeptRad\Generator\View\Crud;

use CeptRad\Generator\AbstractGenerator;

class ListView extends AbstractGenerator
{
    /**
     * Generate paginated table with detail / edit / delete buttons
     *
     * @param string $entity entity used for routing
     * @param array $columns array with all columns to display
     * @param string $namespace Module namespace
     * @return string
     */
    public function generate($entity, array $columns, $namespace)
    {
        $table = $this->getTable($entity, $columns, $namespace);
        $file = new \Zend\Code\Generator\FileGenerator();
        $file->setBody($table);
        $this->setFile($file);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }

    /**
     * Get html table
     *
     * @param array $columns
     * @param string $namespace
     * @return string
     */
    protected function getTable($entity, $columns, $namespace)
    {
        $table = '<table class="table table-hover table-striped">'.PHP_EOL;
        $table .= '<?php foreach($this->paginator as $item):?>'.PHP_EOL;
        foreach ($columns as $column) {
            $table .= '<td><?php echo $this->escapeHtml($item->'.$column.');?></td>'.PHP_EOL;
        }
        $table .= '<td class="btn-col">'.PHP_EOL;
        $table .= '     <a class="btn" href="<?php echo $this->url("'.$namespace.'/default", array("controller" => "'.$entity.'", "action" => "edit"), array("query" => array("id" => $item->id)));?>">'.PHP_EOL;
        $table .= '     <?php echo $this->translate("edit", "'.$namespace.'");?>'.PHP_EOL;
        $table .= '     </a>'.PHP_EOL;
        $table .= '</td>'.PHP_EOL;
        $table .= '<td class="btn-col">'.PHP_EOL;
        $table .= '     <a class="btn btn-danger" href="<?php echo $this->url("'.$namespace.'/default", array("controller" => "'.$entity.'", "action" => "delete"), array("query" => array("id" => $item->id)));?>">'.PHP_EOL;
        $table .= '     <?php echo $this->translate("delete", "'.$namespace.'");?>'.PHP_EOL;
        $table .= '     </a>'.PHP_EOL;
        $table .= '</td>'.PHP_EOL;
        $table .= '</table>'.PHP_EOL;
        $table .= '<?php echo $this->paginationControl($this->paginator,"Sliding","'.strtolower($namespace).'/pagination/sliding", array("route" => "'.$namespace.'/'.$entity.'List"));?>'.PHP_EOL;
        return $table;
    }
}
