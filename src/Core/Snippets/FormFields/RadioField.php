<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\Radio;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * Renders a radio buttons field
 */
class RadioField extends TemplateSnippet
{
    /**
     * The check list to be rendered
     * @var Radio
     */
    protected $field;
    
    /**
     * The html attributes
     * @var array
     */
    protected $attribs;
    /**
     * Creates a new instance of the radio field
     * @param Radio $field
     */
    function __construct(Radio $field)
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
    }
}