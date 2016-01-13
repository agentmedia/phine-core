<?php

namespace Phine\Bundles\Core\Logic\Module\Enums;
use Phine\Framework\System\Enum;

class ModuleLocation extends Enum
{
    /**
     * Represents the frontend module location
     * @return ModuleLocation
     */
    public static function Frontend()
    {
        return new self('Frontend');
    }
    
    
    /**
     * Represents the frontend module location
     * @return ModuleLocation
     */
    public static function Backend()
    {
        return new self('Backend');
    }
}
