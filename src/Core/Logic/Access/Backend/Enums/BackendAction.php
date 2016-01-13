<?php

namespace Phine\Bundles\Core\Logic\Access\Backend\Enums;
use Phine\Framework\Access\Base\Action;

/**
 * Access actions as additionally needed to base actions in phine cms backend
 */
class BackendAction extends Action
{
    /**
     * Represents the action of moving items
     * @return BackendAction
     */
    static function Move()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the action of adding or removing admin status to a user
     * @return BackendAction
     */
    static function ChangeIsAdmin()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the action of assigning user groups
     * @return BackendAction
     */
    static function AssignGroups()
    {
        return new self (__FUNCTION__);
    }
    
    /**
     * Represents the action of locking modules for user groups
     * @return BackendAction
     */
    static function LockModules()
    {
        return new self (__FUNCTION__);
    }
}

