<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Database\Core\Layout;
use Phine\Database\Access;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\File;
use Phine\Database\Core\Area;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Tree\AreaListProvider;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class LayoutList extends BackendModule
{
    use Traits\TableObjectRemover;
    
    /**
     * A list of layouts
     * @var Layout[]
     */
    protected $layouts;
    function Init()
    {
        $sql = Access::SqlBuilder();
        $tblLayout = Layout::Schema()->Table();
        
        $orderBy = $sql->OrderList($sql->OrderAsc($tblLayout->Field('Name')));
        
        
        $this->layouts = Layout::Schema()->Fetch(false, null, $orderBy);
        
        return parent::Init();
    }
    
    /**
     * The url to the page with the layout edit/create form
     * @param Layout $layout If layout is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(Layout $layout = null)
    {
        
        $args = array();
        if ($layout)
        {
            $args['layout'] = $layout->GetID();
        }
        return BackendRouter::ModuleUrl(new LayoutForm(), $args);
    }
    
    /**
     * The side navigation index
     * @return int Returns the index in the side nav
     */
    public function SideNavIndex()
    {
        return 2;
    }
    
    /**
     * Gets the layout that is requested to be removed
     * @return Layout The removal layout
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? Layout::Schema()->ByID($id) : null;
    }
    
    protected function BeforeRemove(Layout $layout)
    {
        $layoutFile = PathUtil::LayoutTemplate($layout);
        if (File::Exists($layoutFile))
        {
            File::Delete($layoutFile);
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportLayoutAction($layout, Action::Delete());
    }
    
    protected function Areas(Layout $layout)
    {
        $list = new AreaListProvider($layout);
        return $list->ToArray();
    }
    
    protected function IsLocked(Area $area)
    {
        return !self::Guard()->Allow(BackendAction::Read(), $area);
    }
    /**
     * True if the layout can be edited
     * @param Layout $layout
     * @return bool
     */
    protected function CanEdit(Layout $layout)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $layout);
    }
    
    /**
     * True if user can create a new layout
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Layout());
    }
    
    
    /**
     * True if layout can be deleted
     * @param Layout $layout The layout
     * @return boo
     */
    protected function CanDelete(Layout $layout)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $layout)
            && self::Guard()->Allow(BackendAction::UseIt(), new LayoutForm());
    }
    
    
    
    /**
     * The link to the area contents
     * @param Area $area The area
     * @return string Returns the url of the area content tree
     */
    protected function AreaUrl(Area $area)
    {
        return BackendRouter::ModuleUrl(new LayoutContentTree(), array('area'=>$area->GetID()));
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the overview
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new Overview());
    }
    
    protected function TemplateFormUrl(Layout $layout)
    {
        return BackendRouter::ModuleUrl(new LayoutTemplateForm(), array('layout'=>$layout->GetID()));
    }
}