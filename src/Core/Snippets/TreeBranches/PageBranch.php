<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches;

use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Database\Core\Page;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Modules\Backend\PageForm;
use Phine\Database\Core\Area;
use Phine\Database\Access;
use Phine\Bundles\Core\Modules\Backend\PageContentTree;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Tree\AreaListProvider;

class PageBranch extends TemplateSnippet
{
    /**
     * The currently page
     * @var Page
     */
    protected $page; 
    
    /**
     * The current child
     * @var Page
     */
    protected $child;
    
    /**
     *
     * @var PageTreeProvider
     */
    private $tree;
    
    /**
     * The layout areas
     * @var Area[]
     */
    protected $areas;
    function __construct(Page $page)
    {
        $this->page = $page;
        $this->tree = new PageTreeProvider($this->page->GetSite());
        $areaList = new AreaListProvider($page->GetLayout());
        $this->areas = $areaList->ToArray();
        $this->child = $this->tree->FirstChildOf($this->page);
    }
    
    protected function NextChild()
    {
        $child = $this->child;
        $this->child = $this->tree->NextOf($this->child);
        return $child;
    }
    
    protected function ParentID()
    {
        $parent = $this->tree->ParentOf($this->page);
        return $parent ? $parent->GetID() : '0';
    }
    
    protected function CreateInUrl()
    {
        $args = array('site'=>$this->page->GetSite()->GetID());
        $args['parent'] = $this->page->GetID();
        return BackendRouter::ModuleUrl(new PageForm(), $args);
    }
    
    
    protected function EditUrl()
    {
        $args = array('page'=>$this->page->GetID());
        return BackendRouter::ModuleUrl(new PageForm(), $args);
    }
    
    protected function CreateAfterUrl()
    {
        $args = array('site'=>$this->page->GetSite()->GetID());
        $args['previous'] = $this->page->GetID();
        $parent = $this->page->GetParent();
        if ($parent)
        {
            $args['parent'] = $parent->GetID(); 
        }
        return BackendRouter::ModuleUrl(new PageForm(), $args);
    }
    
    /**
     * True if page is editable
     * @param Page $page
     * @return bool
     */
    protected function CanEdit()
    {
        return BackendModule::Guard()->Allow(BackendAction::Edit(), $this->page);
    }
    
     /**
     * True if page can be inserted
     * @param Page $page The parent page
     * @return bool
     */
    protected function CanCreateIn()
    {
        return BackendModule::Guard()->Allow(BackendAction::Create(), $this->page);
    }
    
     /**
     * True if page can be appended
     * @param Page $page The page before
     * @return bool
     */
    protected function CanCreateAfter()
    {
        $parent = $this->tree->ParentOf($this->page);
        if ($parent)
        {
            return BackendModule::Guard()->Allow(BackendAction::Create(), $parent);
        }
        return BackendModule::Guard()->GrantAddPageToSite($this->page->GetSite())
                ->ToBool();
    }
    
    /**
     * True if page is deletable
     * @param Page $page
     * @return bool
     */
    protected function CanDelete()
    {
        return BackendModule::Guard()->Allow(BackendAction::Delete(), $this->page);
    }
    
    /**
     * True if page is moveable
     * @param Page $page
     * @return bool
     */
    protected function CanMove()
    {
        return BackendModule::Guard()->Allow(BackendAction::Move(), $this->page);
    }
    
    
    /**
     * True if the area is locked
     * @param Area $area
     * @return bool
     */
    protected function IsLocked(Area $area)
    {
        return !BackendModule::Guard()->Allow(BackendAction::Read(), $area);
    }
    /**
     * Gets the content tree url
     * @param Area $area
     * @return string Returns the page content tree url
     */
    protected function AreaUrl(Area $area)
    {
        $params = array();
        $params['page'] = $this->page->GetID();
        $params['area'] = $area->GetID();
        return BackendRouter::ModuleUrl(new PageContentTree(), $params);
    }
}

