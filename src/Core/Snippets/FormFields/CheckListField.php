<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\CheckList;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * Renders an input field of type text and its label before
 */
class CheckListField extends TemplateSnippet
{
    /**
     * The check list to be rendered
     * @var CheckList
     */
    protected $field;
    
    /**
     * The html attributes
     * @var array
     */
    protected $attribs;
    /**
     * Creates a new instance of the check list field
     * @param CheckList $field
     */
    function __construct(CheckList $field)
    {
        $this->field = $field;
        
        $this->attribs = $field->GetHtmlAttributes();
    }
}