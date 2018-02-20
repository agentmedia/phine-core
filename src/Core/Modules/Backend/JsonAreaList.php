<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Modules\Backend\Base\JsonTree;
use App\Phine\Database\Core\Area;
use Phine\Bundles\Core\Logic\Tree\AreaListProvider;
use Phine\Bundles\Core\Logic\Hooks\IDeleteHook;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;


class JsonAreaList extends JsonTree
{
    
    /**
     *
     * @var IDeleteHook[]
     */
    private static $deleteHooks = array();
    
    /**
     * Adds a hook being executed before an area is deleted
     * @param IDeleteHook $hook
     */
    static function AddDeleteHook(IDeleteHook $hook)
    {
        self::$deleteHooks[] = $hook;
    }
    
    protected function TableSchema()
    {
        return Area::Schema();
    }
    
    protected function TreeProvider()
    {
        return new AreaListProvider($this->item->GetLayout());
    }
    
    /**
     * Execute delete hooks
     */
    protected function BeforeDelete()
    {
        foreach (self::$deleteHooks as $hook)
        {
            $hook->BeforeDelete($this->item);
        }
        $logger = new Logger(BackendModule::Guard()->GetUser());
        $logger->ReportAreaAction($this->item, Action::Delete());
    }
}
