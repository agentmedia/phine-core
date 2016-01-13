<?php

namespace Phine\Bundles\Core\Snippets\BackendRights\Base;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Snippets\FormFields\CheckboxField;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Framework\Database\Objects\TableObject;

/**
 * Base class for backend rights checkboxes
 */
abstract class RightsCheckboxes extends TemplateSnippet
{
    
    /**
     * The prefix for field names
     * @var string
     */
    protected $namePrefix = '';
    
    /**
     * Creates the rights checkboxes
     * @param string $namePrefix Optional field name prefix
     */
    function __construct($namePrefix = '')
    {
        $this->namePrefix = $namePrefix;
    }
    
    protected abstract function LabelPrefix();
    /**
     * @return TableObject
     */
    public abstract function Rights();
    
    /**
     * @return TableObject
     */
    public abstract function ParentRights();
    
    /**
     * Saves the rights
     */
    public abstract function Save();
    /**
     * Renders a specific checkbox
     * @param string $name
     * @return string
     */
    protected function RenderCheckbox($name)
    {
        $checkbox = new Checkbox($this->namePrefix . $name);
        $class = new \ReflectionClass($this);
        $checkbox->SetLabel(Trans('Core.' . $class->getShortName() . '.' . $name));
        if ($this->Value($name))
        {
            $checkbox->SetChecked();
        }
        $field = new CheckboxField($checkbox);
        return $field->Render();
    }
    /**
     * Gets the posted value of the name, optional name prefix automatically attached
     * @param string $name The checkbox name without prefix
     */
    protected function Value($name)
    {
        if (Request::IsPost())
        {
            return (bool)trim(Request::PostData($this->namePrefix . $name));
        }
        if ($this->Rights())
        {
            return $this->Rights()->$name;
        }
        if ($this->ParentRights())
        {
            return $this->ParentRights()->$name;
        }
        return false;
    }
}
