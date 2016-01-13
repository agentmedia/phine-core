<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
/**
 * Represents the backend side navigation containing all required backend modules
 */
class SideNav extends BackendModule
{
    protected $bundleModules = array();
    /**
     * Initializes the side navigation
     */
    protected function Init()
    {
        $this->bundleModules = ClassFinder::BackendNavModules();
        
        return parent::Init();
    }
    
}

