<?php

namespace Phine\Bundles\Core\Modules\Backend;

use App\Phine\Database\Access;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use App\Phine\Database\Core\Membergroup;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;
use Phine\Framework\Database\Objects\TableObject;

/**
 * The member group list in the backend
 */
class MembergroupList extends BackendModule
{
    use Traits\TableObjectRemover;
    /**
     *
     * @var Membergroup[]
     */
    protected $groups;
    
    /**
     * Initiaizes the set of groups
     */
    protected function Init()
    {
        $sql = Access::SqlBuilder();
        $tbl = Membergroup::Schema()->Table();
        $order = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $this->groups = Membergroup::Schema()->Fetch(false, null, $order);
        return parent::Init();
    }
    
    /**
     * The url to the page with the member group edit/create form
     * @param Membergroup $group If member group is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(Membergroup $group = null)
    {
        $args = array();
        if ($group)
        {
            $args['membergroup'] = $group->GetID();
        }
        return BackendRouter::ModuleUrl(new MembergroupForm(), $args);
    }
    
    
    function SideNavIndex()
    {
        return 7;
    }
    
    /**
     * True if a new member group can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Membergroup());
    }
    
    /**
     * True if member can be edited
     * @param Membergroup $group
     * @return bool
     */
    protected function CanEdit(Membergroup $group)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $group) && 
                self::Guard()->Allow(BackendAction::UseIt(), new MembergroupForm());
    }
    
    /**
     * True if user can delete the member group
     * @param Membergroup $group
     * @return bool
     */
    protected function CanDelete(Membergroup $group)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $group);
    }
    
    
    
    /**
     * Gets the member group for removal if delete id is posted
     * @return MembergroupList
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? new Membergroup($id) : null;
    }
    
    protected function BeforeRemove(TableObject $deleteObject)
    {
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportMemberGroupAction($deleteObject, Action::Delete());
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

