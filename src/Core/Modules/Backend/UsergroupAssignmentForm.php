<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Database\Access;
use Phine\Database\Core\Usergroup;
use Phine\Database\Core\User;
use Phine\Database\Core\UserUsergroup;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Snippets\FormParts\FieldColumnizer;
use Phine\Framework\System\String;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

class UsergroupAssignmentForm extends BackendForm
{
    /**
     * List of all groups
     * @var Usergroup[]
     */
    protected $groups;
    
    /**
     *
     * @var User
     */
    protected $user;
    
    /**
     * The current user groups
     * @var Usergroup[]
     */
    private $currentGroups;
    
    /**
     * The group check box representation
     * @var FieldColumnizer
     */
    protected $groupCheckBoxes;
    /**
     * Initializes the form
     * @return boolean
     */
    protected function Init()
    {
        $this->InitGroups();
        $this->AddGroupCheckBoxes();
        $this->AddSubmit();
        return parent::Init();
    }
    /**
     * Gets and checks the requested user
     * @return boolean False if processing can continue
     */
    protected function BeforeInit()
    {
        $this->user = User::Schema()->ByID(Request::GetData('user'));
        if (!$this->user || !self::Guard()->Allow(BackendAction::AssignGroups(), $this->user))
        {
            //TODO: Error message
            Response::Redirect(BackendRouter::ModuleUrl(new UserList()));
        }
        return parent::BeforeInit();
    }
    
    private function InitGroups()
    {
        $sql = Access::SqlBuilder();
        $tblGroup = Usergroup::Schema()->Table();
        $order = $sql->OrderAsc($tblGroup->Field('Name'));
        $this->groups = Usergroup::Schema()->Fetch(false, null, $sql->OrderList($order));
    }
    
    
    private function AddGroupCheckBoxes()
    {
        $this->currentGroups = array();
        $this->groupCheckBoxes = new FieldColumnizer($this);
        foreach ($this->groups as $group)
        {
            $name = 'Usergroup_' . $group->GetID();
            $cb = new Checkbox($name, $group->GetID());
            if ($this->HasGroup($group))
            {
                $this->currentGroups[] = $group;
                $cb->SetChecked();
            }
            $this->AddField($cb, false, $group->GetName());
            $this->groupCheckBoxes->AddField($name);
        }
    }
    
    private function HasGroup(Usergroup $group)
    {
        return UserUsergroup::Schema()->Count(false, $this->UserGroupWhere($group)) > 0;
    }
    protected function OnSuccess()
    {
        $postedGroupIDs = $this->PostedGroupIDs();
        foreach ($this->currentGroups as $currentGroup)
        {
            if (!in_array($currentGroup->GetID(), $postedGroupIDs))
            {
                $this->DeleteGroupAssignment($currentGroup);
            }
        }
        foreach ($postedGroupIDs as $postedGroupID)
        {
            if (!$this->HasGroup(new Usergroup($postedGroupID)))
            {
                $uug = new UserUsergroup();
                $uug->SetUser($this->user);
                $uug->SetUserGroup(new Usergroup($postedGroupID));
                $uug->Save();
            }
        }
        Response::Redirect(BackendRouter::ModuleUrl(new UserList()));
    }
    
    private function UserGroupWhere(Usergroup $group)
    {
        $sql = Access::SqlBuilder();
        $tblUug = UserUsergroup::Schema()->Table();
        return $sql->Equals($tblUug->Field('User'), $sql->Value($this->user->GetID()))
                     ->And_($sql->Equals($tblUug->Field('UserGroup'), $sql->Value($group->GetID())));
    }
    protected function DeleteGroupAssignment(Usergroup $group)
    {
        UserUsergroup::Schema()->Delete($this->UserGroupWhere($group));
    }
    
    protected function PostedGroupIDs()
    {
        $result = array();
        $post = Request::PostArray();
        foreach ($post as $name => $value)
        {
            if (!String::StartsWith('Usergroup_', $name))
            {
                continue;
            }
            $result[] = $value;
        }
        return $result;
    }

}

