<?php

namespace Phine\Bundles\Core\Logic\Logging\Interfaces;
use App\Phine\Database\Core\Content;
use App\Phine\Database\Core\Container;
/**
 * Interface to retrieve container usage as content
 * Internal, once implemented interface to keep BuiltIn and Core bundles separated
 */
interface IContainerReferenceResolver
{
    /**
     * Fetches container referenced in a given content
     * @param Content $content
     * @return Container Gets the referenced container or null if none is referenced
     */
    function GetReferencedContainer(Content $content);
}
