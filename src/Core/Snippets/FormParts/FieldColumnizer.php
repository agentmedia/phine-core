<?php

namespace Phine\Bundles\Core\Snippets\FormParts;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Bundles\Core\Logic\Module\BackendForm;
/**
 * Automatically arranges fields in columns
 */
class FieldColumnizer extends TemplateSnippet
{
    /**
     * The field names
     * @var string[]
     */
    protected $fields;
    /**
     *
     * @var BackendForm
     */
    protected $form;
    
    /**
     * The fields sorted into col 
     * @var array
     */
    protected $columnFields;
    /**
     * The maximum amount of columns
     * @var int
     */
    protected $maxColumns;
    
    /**
     *
     * @var int
     */
    protected $numColumns;
    
    protected $colspan; 
    
    protected $perColumn;
    
    /**
     * Creates the new field columnizer
     * @param BackendForm $form The form where the fields are added
     * @param int $maxColumns The maximum amount of allowed columns
     * @throws \Exception Raises an error in case the max Columns doesn't match the 12 columns grid
     */
    function __construct(BackendForm $form, $maxColumns = 4)
    {
        $this->form = $form;
        if (12 % $maxColumns != 0)
        {
            throw new \Exception(Trans('Core.FieldColumnizer.Error.MaxColumns.MustDivide12'));
        }
        $this->maxColumns = (int)$maxColumns;
        $this->fields = array();
    }
    
    
    function AddField($name)
    {
        $this->fields[] = $name;
    }
    
    private function CalcColumnCounts()
    {
        $num = $this->maxColumns;
        $fieldCount = count($this->fields);
        $this->perColumn = $fieldCount;
        while ($num > 1)
        {
           
            if (12 % $num  != 0 || $num > $fieldCount)
            {
                --$num;
                continue;
            } 
            if ($fieldCount % $num == 0)
            {
                $this->perColumn = round($fieldCount / $num);
                break;
            }
            
            $remainder = $fieldCount % ($num - 1);
           
            $perColumn = round(($fieldCount - $remainder) / ($num - 1));
            if ($remainder > 0 && $remainder < $perColumn)
            {
                
                $this->perColumn = $perColumn;
                break;
            }
            --$num;
        }
        $this->numColumns = $num;
    }
    private function AssignFieldsToColumns()
    {
        $this->CalcColumnCounts();
        $this->columnFields = array();
        $this->colspan = 12 / $this->numColumns;
        $inColumn = 0;
        $column = 0;
        
        for ($idx = 0; $idx < count($this->fields); ++$idx)
        {
            if ($inColumn >= $this->perColumn)
            {
                $inColumn = 0;
                ++$column;
            }
            if (!isset($this->columnFields[$column]))
            {
                $this->columnFields[$column] = array();
            }
            $this->columnFields[$column][] = $this->fields[$idx];
            ++$inColumn;
        }
    }
    
    
    public function Render()
    {
        if (count($this->fields))
        {
            $this->AssignFieldsToColumns();
        }
        return parent::Render();
    }
}

