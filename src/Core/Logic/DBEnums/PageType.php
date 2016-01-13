<?php

namespace Phine\Bundles\Core\Logic\DBEnums;
use Phine\Framework\System\Enum;

/**
 * Represents the type of a page
 */
class PageType extends Enum
{
    /**
     * Represents a normally rendered page
     * @return PageType Returns the normal page type
     */
    static function Normal()
    {
        return new self(__FUNCTION__);
    }
    
    
    /**
     * Represents a permanent redirect
     * @return PageType Returns the page type for a 301 redirect
     */
    static function RedirectPermanent()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents a temporary redirect
     * @return PageType Returns the page type for a 302 redirect
     */
    static function RedirectTemporary()
    {
        return new self(__FUNCTION__);
    }
    
    
    /**
     * Represents page shown when a page is not found
     * @return PageTypes Returns the page type for the 404 page
     */
    static function NotFound()
    {
        return new self(__FUNCTION__);
    }
}