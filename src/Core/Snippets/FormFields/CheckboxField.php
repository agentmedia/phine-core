<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * Renders an input field of type text and its label before
 */
class CheckboxField extends TemplateSnippet
{
    protected $field;
    protected $attribs;
    function __construct(Checkbox $field)
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
    }
}
