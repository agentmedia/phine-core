<?php
namespace Phine\Bundles\Core\Logic\Module\Enums;

use Phine\Framework\System\Enum;

/**
 * Represents the content location
 */
class ContentLocation extends Enum
{
    
    /**
     * Represents content location "layout"
     * @return ContentLocation
     */
    static function Layout()
    {
        return new self('Layout');
    }
    
    /**
     * Represents content location "page"
     * @return ContentLocation
     */
    static function Page()
    {
        return new self('Page');
    }
    
    /**
     * Represents content location "container"
     * @return ContentLocation
     */
    static function Container()
    {
        return new self('Container');
    }
}


