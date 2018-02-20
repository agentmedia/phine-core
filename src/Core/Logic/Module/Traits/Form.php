<?php
namespace Phine\Bundles\Core\Logic\Module\Traits;

use Phine\Framework\System\Http\RequestMethod;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Str;

use Phine\Framework\Validation\Validator;
use Phine\Framework\Wording\Worder;


use Phine\Framework\FormElements\Interfaces\IFormField;
use Phine\Framework\FormElements\Interfaces\IFormElement;
use Phine\Framework\FormElements\Collection;
use Phine\Framework\FormElements\Fields\FormField;
use Phine\Framework\FormElements\Fields\Submit;
use Phine\Framework\FormElements\Fields\Textarea;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Framework\FormElements\Fields\CheckList;
use Phine\Framework\FormElements\Fields\Radio;

use Phine\Bundles\Core\Snippets\FormFields;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Framework\FormElements\Fields\Custom;

/**
 * Trait for form modules
 */
trait Form
{
    /**
     * The name of the field that must be submitted to tirgger the form
     * @var string
     */
    private $triggerName;
    /**
     * The elements
     * @var Collection
     */
    private $elements;
    
    /**
     * Return a collection of form elements for addin form fields
     * @return Collection
     */
    protected function Elements()
    {
        if (!$this->elements)
        {
            $this->elements = new Collection(); 
        }
        return $this->elements;
    }
    
    protected function TriggerName() {
        return $this->triggerName;
    }
    
    /**
     * Defines if the form is triggered
     * @return boolean True if the form must be evaluated and saved
     */
    protected function IsTriggered()
    {
        return Request::MethodData($this->Method(), $this->triggerName) !== null;
    }
    
    
    /**
     * Defines what happens when form is evaluated and successfully validated
     * @return boolean Return true if no output shall be generated furthermore
     */
    protected abstract function OnSuccess();
    
    /**
     * Gets the http method for the form; can be overridden for GET forms
     * @return RequestMethod The HTTP method of the form (post or get)
     */
    protected function Method()
    {
        return RequestMethod::Post();
    }
    
    /**
     * Overrides the template module method to realize form logic
     * @return boolean
     */
    protected function BeforeGather()
    {  
        if ($this->IsTriggered() && $this->Elements()->Check(Request::MethodArray($this->Method())))
        {
            return $this->OnSuccess();
        }
        return parent::BeforeGather();
    }
    /**
     * Gets the bundle name
     * @return string Returns the bundle name
     */
    public abstract function MyBundle();
    
    /**
     * Gets the module name
     * @return string Returns the name of the module
     */
    public abstract function MyName();
    
    
    /**
     * Gets the default translation placeholder for the label 
     * @param string $name The name
     * @return string Returns the field label's translation placeholder
     */
    protected function Label($name)
    {
        return $this->MyBundle() . '.' . $this->MyName() . '.' . $name;
    }
    
    
    /**
     * Gets the default translation placeholder for the desription 
     * @param string $name The name
     * @return string Returns the field description's translation placeholder
     */
    protected function FieldDescription($name)
    {
        return $this->AttributePlaceholder($name, 'description');
    }
    
    /**
     * Gets the default translation placeholder for a field attribute
     * @param string $name The name of the field
     * @param string attribute The field attribute
     * @return string Returns the field attribute's translation placeholder
     */
    protected function AttributePlaceholder($name, $attribute)
    {
        return $this->Label($name) . '.' . ucfirst($attribute);
    }
    
    /**
     * Gets a default error prefix for validations
     * @param string $name
     * @return string
     */
    protected function ErrorPrefix($name)
    {
        return $this->Label($name) . '.';
    }
    
    /**
     * 
     * @param IFormField $field The field
     * @param boolean $noLabel True if no label shall be generated
     * @param string $label Custom label; if omitted, it will be generated
     */
    protected function AddField(IFormField $field,  $noLabel = false, $label = '')
    {
        if (!$noLabel)
        {
            if (!$label)
            {
                $label = Worder::Replace($this->Label($field->GetName()));
            }
            $field->SetLabel($label);
        }
        $this->Elements()->AddField($field);
    }
    /**
     * Sets the field as required
     * @param string $name The name of the required field
     * @param string $errorPrefix The label prefix, auto calculated if omitted
     */
    protected function SetRequired($name, $errorPrefix = '')
    {
        $field = $this->GetElement($name);
        if (!$field instanceof FormField)
        {
            throw new \InvalidArgumentException("$name is not a field in the form elements");
        }
        if (!$errorPrefix)
        {
            $errorPrefix = $this->ErrorPrefix($name);
        }
        $field->SetRequired($errorPrefix);
    }
    /**
     * Gets the element with the given name
     * @param string $name
     * @return IFormElement
     */
    public function GetElement($name)
    {
        $element = $this->Elements()->GetElement($name);
        if (!$element)
        {
            throw new \Exception(Trans('Core.Form.Error.ElementNotFound.Name_{0}', $name));
        }
        return $element;
    }
    
    /**
     * Adds a validator to the name
     * @param string $name
     * @param Validator $validator
     * @param string $errorPrefix A custom error prefix
     */
    protected function AddValidator($name, Validator $validator, $errorPrefix = '')
    {
        $field = $this->GetElement($name);
        if (!$errorPrefix)
        {
            $errorPrefix = $this->ErrorPrefix($name);
        }
        $validator->SetErrorLabelPrefix($errorPrefix);
        $field->AddValidator($validator);
    }
    /**
     * Adds a submit button that triggers the form logic on submit
     * @param name The button name; if omitted, a name is generated by bundle and module name
     * @param label The visible button label; if omitted, a label is generated
     * @return Submit Returns the added submit button
     */
    protected function AddSubmit($name = '', $label = '')
    {
        $defaultLabel = $this->Label('Submit');
        if (!$name)
        {
            //html attributes better without dot
            $name = str_replace('.', '-', $defaultLabel);
        }
        if (!$label)
        {
            $label = Worder::Replace($defaultLabel);
        }
        $this->triggerName = $name;
        $field = new Submit($name, $label);
        $this->Elements()->AddField($field);
        return $field;
    }
    
    
    /**
     * The form value as submitted
     * @param string $name The field name
     * @param boolean $trim If false, no string trimming is performed
     * @return string Returns the submitted field value 
     */
    protected function Value($name, $trim = true)
    {
        $value = Request::MethodData($this->Method(), $name);
        if ($trim)
        {
            return Str::Trim($value);
        }
        return $value;
    }
    
    
    /**
     * Finds the snippet for a form field; can be overriden for customization
     * @param IFormField $field
     * @return TemplateSnippet Returns the associated snippet renderer
     */
    protected function FindSnippet(IFormField $field)
    {
        if ($field instanceof Input && 
                ($field->GetType() == Input::TypeText || 
                $field->GetType() == Input::TypePassword ||
                $field->GetType() == Input::TypeColor))
        {
            return new FormFields\TextInputField($field);
        }
        
        if ($field instanceof Input && 
                $field->GetType() == Input::TypeHidden)
        {
            return new FormFields\HiddenInputField($field);
        }
        
        if ($field instanceof Input && 
                $field->GetType() == Input::TypeFile)
        {
            return new FormFields\FileInputField($field);
        }
        if ($field instanceof Custom)
        {
            return new FormFields\CustomField($field);
        }
        if ($field instanceof Select)
        {
            return new FormFields\SelectField($field);
        }
        
        if ($field instanceof Textarea)
        {
            return new FormFields\TextareaField($field);
        }
        
        if ($field instanceof Checkbox)
        {
            return new FormFields\CheckboxField($field);
        }
        
        if ($field instanceof CheckList)
        {
            return new FormFields\CheckListField($field);
        }
        
        if ($field instanceof Submit)
        {
            return new FormFields\SubmitField($field);
        }
        
        if ($field instanceof Radio) {
            return new FormFields\RadioField($field);
        }
    }
    
    /**
     * Rennders the form element with the given name
     * @param string $name
     * @return string
     */
    public function RenderElement($name)
    {
        return $this->FindSnippet($this->GetElement($name))->Render();
    }
    
    /**
     * Renders the button that submits the form for processing; if unique
     * @return string
     */
    public function RenderSubmit()
    {
        return $this->FindSnippet($this->GetElement($this->triggerName))->Render();
    }
    
    /**
     * Sets a translatable attribute of the field to a default placeholder
     * @param string $name The field name
     * @param string $attribute The attribute name
     */
    protected function SetTransAttribute($name, $attribute)
    {
        $field = $this->GetElement($name);
        $field->SetHtmlAttribute($attribute,
                Worder::Replace($this->AttributePlaceholder($name, $attribute)));
    }
    
    
    /**
     * Sets a description with a default placeholder for translation
     * @param string $name The field name
     */
    protected function SetTransDescription($name)
    {
        $field = $this->GetElement($name);
        $field->SetDescription(Worder::Replace($this->FieldDescription($name)));
    }
    
    
    
}

