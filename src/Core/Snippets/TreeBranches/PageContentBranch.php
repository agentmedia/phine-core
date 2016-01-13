<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches;

use Phine\Database\Core\PageContent;
use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Database\Core\Page;
use Phine\Database\Core\Area;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Access\Base\GrantResult;

/**
 * The page content branch
 */
class PageContentBranch extends Base\ContentBranch
{
    /**
     *
     * @var Page
     */
    private $page;
    
    /**
     *
     * @var Area
     */
    private $area;
    /**
     * Initializes a new page content branch
     * @param PageContent $pageContent The page content 
     */
    function __construct(PageContent $pageContent)
    {
        $this->page = $pageContent->GetPage();
        $this->area = $pageContent->GetArea();
        parent::__construct($pageContent);
    }
    
    /**
     * The edit/create url base parameters
     */
    protected function EditParams()
    {
        $params = array();
        $params['page'] = $this->page->GetID();
        $params['area'] = $this->area->GetID();
        return $params;
    }
    
    /**
     * Returns the tree provider
     * @return PageContentTreeProvider
     */
    protected function TreeProvider()
    {
        return new PageContentTreeProvider($this->page, $this->area);
    }

    protected function GrantCreateInRoot()
    {
        return BackendModule::Guard()->GrantAddContentToPageArea($this->page, $this->area);
    }

}

