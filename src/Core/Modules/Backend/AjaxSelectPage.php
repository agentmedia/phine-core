<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Logic\Module\AjaxBackendForm;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use App\Phine\Database\Core\Site;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Access;
use Phine\Framework\System\Http\Request;
use App\Phine\Database\Core\PageUrl;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;

/**
 * Ajax page selector
 */
class AjaxSelectPage extends AjaxBackendForm
{

    /**
     * The currently rendered site for the tree
     * @var Site
     */
    private $site;

    /**
     *
     * @var Page
     */
    private $page;

    /**
     * The selected page
     * @var Page
     */
    protected $selected;

    /**
     *
     * @var PageTreeProvider
     */
    private $tree;

    /**
     * Array of available sites
     * @var Site[]
     */
    protected $sites;

    /**
     * The prefix for the relevant html element identifiers
     * @var string
     */
    private $prefix;

    /**
     * List of disables page ids
     * @var int[]
     */
    private $disabledPageIDs;

    /**
     * true if no parameters and no url fragment is requured
     * @var boolean
     */
    private $pageOnly;

    protected function Init()
    {
        $pageID = Request::GetData('page');
        $this->pageOnly = (bool) Request::GetData('pageOnly');
        $this->selected = Page::Schema()->ByID($pageID);
        $this->prefix = Request::GetData('prefix');
        $this->disabledPageIDs = Request::GetArray('disabled');
        $this->InitSites();
        
        $this->AddSubmit();
        return parent::Init();
    }
    
    private function InitSites()
    {
        $siteID = Request::GetData('site');
        if ($siteID)
        {
            $this->sites = array(Site::Schema()->ByID($siteID));
            return;
        }
        $sql = Access::SqlBuilder();
        $tblSite = Site::Schema()->Table();
        $orderBy = $sql->OrderList($sql->OrderAsc($tblSite->Field('Name')));
        $this->sites = Site::Schema()->Fetch(false, null, $orderBy);
    }

    /**
     * Sets the site as current site and returns true if it has pages below
     * @param Site $site The site to set and check for pages
     * @return boolean Returns true if the site has pages
     */
    protected function HasPages(Site $site)
    {
        $this->site = $site;
        $this->tree = new PageTreeProvider($site);
        $this->page = $this->tree->TopMost();
        return (bool) $this->page;
    }

    /**
     * 
     * @return Page Returns the next page after the current page
     */
    protected function NextPage()
    {
        $page = $this->page;
        $this->page = $this->tree->NextOf($this->page);
        return $page;
    }

    /**
     * Checks if the page was disabled
     * @param Page $page The page to check
     * @return boolean Returns true if the page is not selectable
     */
    protected function IsDisabled(Page $page)
    {
        return in_array($page->GetID(), $this->disabledPageIDs);
    }

    protected function OnSuccess()
    {
        $page = $this->Value('page');
        if (!$page) {
            $this->UnsetPage();
            $this->CloseModal();
        }
        else if ($this->pageOnly) {
            $objPage = Page::Schema()->ByID($page);
            $page = $this->SetJSFieldValue('#' . $this->prefix . 'Page', $page);
            $this->SetJSHtml('#' . $this->prefix . 'Name', $objPage->GetName());
            $this->CloseModal();
            return;
        }
        $params = array();
        $params['prefix'] = $this->prefix;
        $params['page'] = $this->Value('page');
        $params['params'] = Request::GetData('params');
        $params['fragment'] = Request::GetData('fragment');
        $this->RedirectModal(BackendRouter::AjaxUrl(new AjaxPageParams(), $params));
    }

    private function UnsetPage()
    {
        $this->SetJSFieldValue('#' . $this->prefix . 'Page', '');
        if (!$this->pageOnly) {
            $this->SetJSFieldValue('#' . $this->prefix . 'Params', '');
            $this->SetJSFieldValue('#' . $this->prefix . 'Fragment', '');
            $this->SetJSHtml('#' . $this->prefix . 'Url', Trans('Core.PageUrlSelector.NoPage'));
        }
        else {
            $this->SetJSHtml('#' . $this->prefix . 'Name', Trans('Core.PageSelector.NoPage'));
        }
    }

}
