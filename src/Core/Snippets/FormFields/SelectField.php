<?php
namespace Phine\Bundles\Core\Snippets\FormFields;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;

/**
 * A renderer for the pure select field
 */
class SelectField extends TemplateSnippet
{
    /**
     *
     * @var Select
     */
    protected $select;
    /**
     * 
     * @param Select $select
     */
    function __construct(Select $select)
    {
        $this->select = $select;
    }
}
