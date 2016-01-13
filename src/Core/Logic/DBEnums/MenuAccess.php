<?php

namespace Phine\Bundles\Core\Logic\DBEnums;
use Phine\Framework\System\Enum;
/**
 * The menu access setting options for a page
 */
class MenuAccess extends Enum
{
    
    /**
     * Only authorized visitors see the menu entry (default)
     * @return MenuAccess
     */
    static function Authorized()
    {
        return new self (__FUNCTION__);
    }
    
    /**
     * Menu item visible also for unauthorized visitors
     * @return MenuAccess
     */
    static function AlwaysVisible()
    {
        return new self (__FUNCTION__);
    }
    
    /**
     * Menu item is always hidden
     * @return MenuAccess
     */
    static function AlwaysHidden()
    {
        return new self (__FUNCTION__);
    }
}

