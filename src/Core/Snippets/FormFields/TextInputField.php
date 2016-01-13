<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\Input;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * Renders an input field of type text and its label before
 */
class TextInputField extends TemplateSnippet
{
    protected $field;
    protected $attribs;
    function __construct(Input $field)
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
    }
}
