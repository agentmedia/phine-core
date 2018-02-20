<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Modules\Backend\Base\JsonTree;
use App\Phine\Database\Core\LayoutContent;
use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class JsonLayoutContentTree extends JsonTree
{
    protected function TableSchema()
    {
        return LayoutContent::Schema();
    }

    protected function TreeProvider()
    {
        return new LayoutContentTreeProvider($this->item->GetArea());
    }
    
    protected function BeforeDelete()
    {
        $logger = new Logger(BackendModule::Guard()->GetUser());
        $logger->ReportContentAction($this->TreeProvider()->ContentByItem($this->item), Action::Delete());
    }
}
