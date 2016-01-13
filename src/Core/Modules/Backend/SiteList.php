<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Database\Access;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Database\Core\Site;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;
use Phine\Framework\Database\Objects\TableObject;

/**
 * The site list in the backend
 */
class SiteList extends BackendModule
{
    use Traits\TableObjectRemover;
    /**
     *
     * @var Site[]
     */
    protected $sites;
    
    /**
     * Initiaizes the set of sites
     */
    protected function Init()
    {
        $sql = Access::SqlBuilder();
        $tbl = Site::Schema()->Table();
        $order = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $sites = Site::Schema()->Fetch(false, null, $order);
        $this->sites = array();
        foreach ($sites as $site)
        {
            if (self::Guard()->Allow(BackendAction::Read(), $site))
            {
                $this->sites[] = $site;
            }
        }
        return parent::Init();
    }
    
    /**
     * The url to the page with the site edit/create form
     * @param Site $site If site is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(Site $site = null)
    {
        $args = array();
        if ($site)
        {
            $args['site'] = $site->GetID();
        }
        return BackendRouter::ModuleUrl(new SiteForm(), $args);
    }
    
    /**
     * Returns the page tree url of a site
     * @param Site $site The site
     * @return string The url of the page tree
     */
    protected function PageTreeUrl(Site $site)
    {
        $args = array('site' => $site->GetID());
        return BackendRouter::ModuleUrl(new PageTree(), $args);
    }
    
    /**
     * The index in the core side navigation list
     * @return int
     */
    function SideNavIndex()
    {
        return 1;
    }
    
    /**
     * True if a new site can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Site());
    }
    
    /**
     * True if site can be edited
     * @param Site $site
     * @return bool
     */
    protected function CanEdit(Site $site)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $site) && 
                self::Guard()->Allow(BackendAction::UseIt(), new SiteForm());
    }
    
    /**
     * True if user can delete the site
     * @param Site $site
     * @return bool
     */
    protected function CanDelete(Site $site)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $site);
    }
    
    
    
    /**
     * Gets the site for removal if delete id is posted
     * @return Site
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? new Site($id) : null;
    }
    
    protected function BeforeRemove(TableObject $deleteObject)
    {
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportSiteAction($deleteObject, Action::Delete());
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the overview
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new Overview());
    }

}

