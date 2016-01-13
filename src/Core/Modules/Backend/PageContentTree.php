<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Database\Core\Page;
use Phine\Database\Core\Area;
use Phine\Database\Core\PageContent;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

class PageContentTree extends BackendModule
{
 
    /**
     * The page of the content tree
     * @var Page
     */
    protected $page;
    
    /**
     * The area of the content tree
     * @var Area
     */
    protected $area;

    /**
     * The tree provider
     * @var PageContentTreeProvider
     */
    private $tree;
    
    /**
     * True if page has contents
     * @var bool
     */
    protected $hasContents = false;
    
    /**
     * The current page content
     * @var PageContent
     */
    protected $pageContent;
    
    
    /**
     * Optional pre-selected page content
     * @var PageContent
     */
    protected $selected;
    

    protected function Init()
    {
        $this->page = new Page(Request::GetData('page'));
        $selectedID = Request::GetData('selected');
        $this->selected = $selectedID ? PageContent::Schema()->ByID($selectedID) :  null;
        if (!$this->page->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new SiteList()));
            return true;
        }
        $this->area = new Area(Request::GetData('area'));
        if (!$this->area->Exists())
        {
            $params = array('site'=>$this->page->GetSite()->GetID());
            Response::Redirect(BackendRouter::ModuleUrl(new PageTree(), $params));
            return true;
        }
        $this->tree = new PageContentTreeProvider($this->page, $this->area);
        $this->pageContent = $this->tree->TopMost();
        
        $this->hasContents = (bool)$this->pageContent;
        return parent::Init();
    }

    protected function CreateFormUrl()
    {
        $args = array('page'=>$this->page->GetID(), 'area'=>$this->area->GetID());
        return BackendRouter::ModuleUrl(new ModuleForm(), $args);
    }

    protected function NextPageContent()
    {
        $pageContent = $this->pageContent;
        $this->pageContent = $this->tree->NextOf($this->pageContent);
        return $pageContent;
    }
    
    
    /**
     * The url for the ajax json response
     * @return string
     */
    protected function JsonUrl()
    {
        return BackendRouter::AjaxUrl(new JsonPageContentTree());
    }
    
    /**
     * True if contents can be added to root element
     * @return boolean
     */
    protected function CanCreateIn()
    {
        return self::Guard()->GrantAddContentToPageArea($this->page, $this->area)
            ->ToBool() && self::Guard()->Allow(BackendAction::UseIt(), new ModuleForm());
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the page tree
     */
    protected function BackLink()
    {
        $params = array('site'=>$this->page->GetSite()->GetID());
        $params['selected'] = $this->page->GetID();
        return BackendRouter::ModuleUrl(new PageTree(), $params);
    }

}
