<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

/**
 * The overview page, shown by default after login
 */
class Overview extends BackendModule
{
    /**
     * Array with bundle names as key and module name array as value
     * @var array
     */
    protected $bundlesModules;
    protected function Init()
    {
        $this->bundlesModules = ClassFinder::BackendNavModules();
        $coreModules = $this->bundlesModules['Core'];
        
        //unset overview module
        unset ($coreModules[0]);
        $this->bundlesModules['Core'] = array_values($coreModules);
        return parent::Init();
    }
    
    protected function ModuleUrl(BackendModule $module)
    {
        return BackendRouter::ModuleUrl($module);
    }
    
    protected function SeparateModules(array $modules)
    {
        $splitIndex = ceil(count($modules) / 2);
        return array(array_slice($modules, 0, $splitIndex), array_slice($modules, $splitIndex)); 
    }
    function SideNavIndex()
    {
        return 0;
    }
    
    protected function SettingsFormLink()
    {
        return BackendRouter::ModuleUrl(new SettingsForm());
    }
   
}

