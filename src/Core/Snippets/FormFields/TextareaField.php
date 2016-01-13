<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\Textarea;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;


/**
 * Renders an input field of type text and its label before
 */
class TextareaField extends TemplateSnippet
{
    protected $field;
    protected $attribs;
    function __construct(Textarea $field)
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
    }
}
