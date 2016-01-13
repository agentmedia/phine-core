<?php
namespace Phine\Bundles\Core\Snippets\FormFields;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Framework\FormElements\Fields\Custom;
/**
 * Renders an input field of type text and its label before
 */
class CustomField extends TemplateSnippet
{
    /**
     * The custom
     * @var Custom
     */
    protected $field;
    /**
     * The html attributes of the rendered input field
     * @var array
     */
    protected $attribs;
    
    /**
     * 
     * @param Custom $field The field
     
     */
    function __construct(Custom $field)
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
    }
}
