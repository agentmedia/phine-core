<?php
namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Fields\Submit;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * Renders a submit field
 */
class SubmitField extends TemplateSnippet
{
    protected $field;
    protected $attribs;
    
    /**
     * 
     * @param Submit $field
     * @param array $attribs optional attributes
     */
    function __construct(Submit $field, array $attribs = array())
    {
        
        $this->field = $field;
        $this->attribs = $attribs;
    }
}
