<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Database\Core\Usergroup;
use Phine\Database\Access;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class UsergroupList extends BackendModule
{
    use Traits\TableObjectRemover;
    /**
     * The user groups
     * @var Usergroup[]
     */
    protected $groups;
    protected function Init()
    {
        $this->InitGroups();
        return parent::Init();
    }
    private function InitGroups()
    {
        $sql = Access::SqlBuilder();
        $tblGroup = Usergroup::Schema()->Table();
        $order = $sql->OrderAsc($tblGroup->Field('Name'));
        $this->groups = Usergroup::Schema()->Fetch(false, null, $sql->OrderList($order));
    }
    
    /**
     * The user group required for deleting
     * @return Usergroup
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? Usergroup::Schema()->ByID($id) : null;
    }
   
    protected function BeforeRemove(TableObject $deleteObject)
    {
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportUserGroupAction($deleteObject, Action::Delete());
    }
    /**
     * True if group can be edited
     * @param Usergroup $group
     * @return bool
     */
    protected function CanEdit(Usergroup $group)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $group)
            && self::Guard()->Allow(BackendAction::UseIt(), new UsergroupForm());
    }
    
    /**
     * True if group can be deleted
     * @param Usergroup $group
     * @return bool
     */
    protected function CanDelete(Usergroup $group)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $group);
    }
    
    
    /**
     * True if a new user group can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Edit(), new Usergroup());
    }
    
    /**
     * The url of the edit/create form
     * @return string
     */
    protected function FormUrl(Usergroup $group = null)
    {
        $params = array();
        if ($group)
        {
            $params['usergroup'] = $group->GetID();
        }
        return BackendRouter::ModuleUrl(new UsergroupForm(), $params);
    }
    
    /**
     * The position in the side navigatio
     * @return int Returns the position index
     */
    public function SideNavIndex()
    {
        return 4;
    }
    /**
     * 
     * True if user can lock modules
     * @return bool Returns true if current user can lock (backend) modules for a group
     */
    protected function CanLockModules(Usergroup $group)
    {
       return self::Guard()->Allow(BackendAction::ChangeIsAdmin(), $group);
    }
    /**
     * Gets the url for the module lock
     * @param Usergroup $group
     * @return string
     */
    protected function ModuleLockFormUrl(Usergroup $group)
    {
        $args = array('usergroup' => $group->GetID());
        return BackendRouter::ModuleUrl(new ModuleLockForm(), $args);
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

