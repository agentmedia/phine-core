<?php

namespace Phine\Bundles\Core\Snippets\FormFields;

use Phine\Framework\FormElements\Collection;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
/**
 * 
 * Renders the errors of a form collection
 */
class FormCollectionErrors extends TemplateSnippet
{
    /**
     *
     * @var Collection
     */
    protected $collection;
    /**
     * 
     * @param Collection $collection
     */
    function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }
}
