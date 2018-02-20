<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Modules\Backend\Base\JsonTree;
use App\Phine\Database\Core\ContainerContent;
use Phine\Bundles\Core\Logic\Tree\ContainerContentTreeProvider;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class JsonContainerContentTree extends JsonTree
{
    protected function TableSchema()
    {
        return ContainerContent::Schema();
    }

    protected function TreeProvider()
    {
        return new ContainerContentTreeProvider($this->item->GetContainer());
    }
    
    protected function BeforeDelete()
    {
        $logger = new Logger(BackendModule::Guard()->GetUser());
        $logger->ReportContentAction($this->TreeProvider()->ContentByItem($this->item), Action::Delete());
    }
}
