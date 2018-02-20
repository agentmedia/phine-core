<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use App\Phine\Database\Core\Site;
use App\Phine\Database\Core\Page;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;;

class PageTree extends BackendModule
{

    /**
     *
     * @var Site
     */
    protected $site;

    /**
     *
     * @var Page
     */
    private $page;

    /**
     *
     * @var PageTreeProvider
     */
    private $tree;
    protected $hasPages = false;
    
    /**
     * Optional pre-selected page
     * @var Page
     */
    protected $selected;
    protected function Init()
    {
        $this->site = new Site(Request::GetData('site'));
        $selectedID = Request::GetData('selected');
        $this->selected = $selectedID ? Page::Schema()->ByID($selectedID) : null;
        if (!$this->site->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new SiteList()));
            return true;
        }
        $this->tree = new PageTreeProvider($this->site);
        $this->page = $this->tree->TopMost();
        $this->hasPages = (bool) $this->page;
        return parent::Init();
    }

    protected function CreateFormUrl()
    {
        $module = new PageForm();
        $args = array('site' => $this->site->GetID());
        return BackendRouter::ModuleUrl($module, $args);
    }

    protected function NextPage()
    {
        $page = $this->page;
        $this->page = $this->tree->NextOf($this->page);
        return $page;
    }
    
    
    protected function JsonUrl()
    {
        return BackendRouter::AjaxUrl(new JsonPageTree());
    }
    
    protected function CanCreateIn()
    {
        return self::Guard()->GrantAddPageToSite($this->site)->ToBool()
                && self::Guard()->Allow(BackendAction::UseIt(), new PageForm());
    }
    
     /**
     * The link for the back button
     * @return string Returns the url to the site list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new SiteList());
    }
}
