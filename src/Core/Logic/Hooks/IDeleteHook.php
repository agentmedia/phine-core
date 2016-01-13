<?php

namespace Phine\Bundles\Core\Logic\Hooks;

/**
 * Interface for delete hooks
 */
interface IDeleteHook
{
    /**
     * Used to clean up resources related to the delete item before removal
     * @param mixed $item The delete item
     */
    function BeforeDelete($item);
}