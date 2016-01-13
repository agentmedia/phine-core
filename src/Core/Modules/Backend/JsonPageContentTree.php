<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Modules\Backend\Base\JsonTree;
use Phine\Database\Core\PageContent;
use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class JsonPageContentTree extends JsonTree
{
    protected function TableSchema()
    {
        return PageContent::Schema();
    }

    protected function TreeProvider()
    {
        return new PageContentTreeProvider($this->item->GetPage(), $this->item->GetArea());
    }
    
    protected function BeforeDelete()
    {
        $logger = new Logger(BackendModule::Guard()->GetUser());
        $logger->ReportContentAction($this->TreeProvider()->ContentByItem($this->item), Action::Delete());
    }
}
