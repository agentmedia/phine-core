<?php

namespace Phine\Bundles\Core\Modules\Backend;

use App\Phine\Database\Access;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use App\Phine\Database\Core\User;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The user list in the backend
 */
class UserList extends BackendModule
{
    use Traits\TableObjectRemover;
    /**
     *
     * @var User[]
     */
    protected $users;
    
    /**
     * Initializes the set of users
     */
    protected function Init()
    {
        $this->InitUsers();
        return parent::Init();
    }
    
    private function InitUsers()
    {
        $sql = Access::SqlBuilder();
        $tbl = User::Schema()->Table();
        $order = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $this->users = User::Schema()->Fetch(false, null, $order);
    }
    
    /**
     * The url to the page with the user edit/create form
     * @param User $user If user is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(User $user = null)
    {
        $args = array();
        if ($user)
        {
            $args['user'] = $user->GetID();
        }
        return BackendRouter::ModuleUrl(new UserForm(), $args); 
    }
    
    /**
     * The url to the form for the user groups
     * @param User $user
     * @return string
     */
    protected function GroupsFormUrl(User $user)
    {
        $args = array('user'=>$user->GetID());
        return BackendRouter::ModuleUrl(new UsergroupAssignmentForm(), $args);
    }
    
    /**
     * True if current user can assign user groups
     * @param User $user The user for assignment
     * @return boolean Returns true if the action is allowed
     */
    protected function CanAssignGroups(User $user)
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $user);
    }
    
    
    /**
     * The index in the core side navigation list
     * @return int
     */
    function SideNavIndex()
    {
        return 5;
    }
    
    /**
     * True if a new user can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new User());
    }
    
    /**
     * Gets the site for removal if delete id is posted
     * @return User
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? User::Schema()->ByID($id) : null;
    }
    
    protected function BeforeRemove(TableObject $deleteObject)
    {
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportUserAction($deleteObject, Action::Delete());
    }
    
    /**
     * True if user can be edited
     * @param User $user
     * @return string
     */
    protected function CanEdit(User $user)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $user)&&
            self::Guard()->Allow(BackendAction::UseIt(), new UserForm());
    }
    
    protected function CanDelete(User $user)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $user);
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

