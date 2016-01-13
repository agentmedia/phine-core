<?php

namespace Phine\Bundles\Core\Logic\Logging\Enums;
use Phine\Framework\System\Enum;
/**
 * Represents the object type for logging
 */
class ObjectType extends Enum
{
    /**
     * Represents the object type site
     * @return ObjectType Site returns the "site" object type
     */
    static function Site()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type page
     * @return ObjectType Site returns the "page" object type
     */
    static function Page()
    {
        return new self(__FUNCTION__);
    }
    
      /**
     * Represents the object type layout
     * @return ObjectType Site returns the "layout" object type
     */
    static function Layout()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type area
     * @return ObjectType Site returns the "area" object type
     */
    static function Area()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type content
     * @return ObjectType Site returns the "content" object type
     */
    static function Content()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type user
     * @return ObjectType Site returns the "user" object type
     */
    static function User()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type user group
     * @return ObjectType Site returns the "user group" object type
     */
    static function UserGroup()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type member
     * @return ObjectType Site returns the "member" object type
     */
    static function Member()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type member group
     * @return ObjectType Site returns the "member group" object type
     */
    static function MemberGroup()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type template
     * @return ObjectType Site returns the "template" object type
     */
    static function Template()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents the object type container
     * @return ObjectType Site returns the "container" object type
     */
    static function Container()
    {
        return new self(__FUNCTION__);
    }
}

