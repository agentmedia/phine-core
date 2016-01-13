<?php

namespace Phine\Bundles\Core\Logic\Logging\Enums;
use Phine\Framework\System\Enum;


/**
 * Represents a loggable action
 */
class Action extends Enum
{
    /**
     * The object has been created
     * @return Action Returns the "create" action
     */
    static function Create()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * The object has been updated
     * @return Action Returns the "update" action
     */
    static function Update()
    {
        return new self(__FUNCTION__);
    }
    
    
    
    /**
     * The object has been removed
     * @return Action Returns the "delete" action
     */
    static function Delete()
    {
        return new self(__FUNCTION__);
    }
    
    
    /**
     * A direct child of the object has been removed
     * @return Action Returns the "delete child" action
     */
    static function ChildDelete()
    {
        return new self(__FUNCTION__);
    }
}
