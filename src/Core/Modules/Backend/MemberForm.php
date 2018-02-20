<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;
use App\Phine\Database\Core\Member;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\Validation\PhpFilter;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Validation\StringLength;
use App\Phine\Database\Core\Membergroup;
use Phine\Bundles\Core\Logic\Util\MembergroupUtil;
use App\Phine\Database\Core\MemberMembergroup;
use App\Phine\Database\Access;
use Phine\Framework\System\Str;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The member form
 */
class MemberForm extends BackendForm
{
    
    /**
     * The member currently edited
     * @var Member
     */
    private $member;
    /**
     * True if member groups exist
     * @var boolean
     */
    protected $groupsExist;
    
    /**
     * Initializes the member form
     * @return boolean Returns false to continue processing
     */
    protected function Init()
    {
        $this->member = new Member(Request::GetData('member'));
        $this->groupsExist = Membergroup::Schema()->Count() > 0;
        $this->AddNameField();
        $this->AddEMailField();
        $this->AddPasswordField();
        $this->AddMemberGroupField();
        $this->AddSubmit();
        return parent::Init();
    }
    /**
     * Adds the name input field
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->member->GetName()));
        $this->SetRequired($name);   
        $this->AddValidator($name, DatabaseCount::UniqueField($this->member, $name));
    }
    
    /**
     * Adds the email field to the form
     */
    private function AddEMailField()
    {
        $name = 'EMail';
        $this->AddField(Input::Text($name, $this->member->GetEMail()));
        $this->SetRequired($name);
        $this->AddValidator($name, PhpFilter::EMail());
        $this->AddValidator($name, DatabaseCount::UniqueField($this->member, $name));
    }
    
    /**
     * Adds the password field to the form
     */
    private function AddPasswordField()
    {
        $name = 'Password';
        $this->AddField(Input::Password($name));
        if (!$this->member->Exists())
        {
            $this->SetRequired($name);
        }
        $this->AddValidator($name, StringLength::MinLength(6));
        
        $this->SetTransAttribute($name, 'placeholder');
    }
    
    private function AddMemberGroupField()
    {
        if (!$this->groupsExist)
        {
            return;
        }
        $name = 'MemberGroup';
        $field = MembergroupUtil::MemberCheckList($name, $this->member);
        $this->AddField($field);
    }
    
    /**
     * Saves the user
     */
    protected function OnSuccess()
    {
        $action = $this->member->Exists() ? Action::Update() : Action::Create();
        $this->member->SetName($this->Value('Name'));
        $this->member->SetEMail($this->Value('EMail'));
        $this->SetPassword();
        $this->member->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportMemberAction($thos->member, $action);
        if ($this->groupsExist && $this->CanAssignGroup())
        {
            $this->SaveGroups();
        }
        Response::Redirect(BackendRouter::ModuleUrl(new MemberList()));
    }
    
    /**
     * Saves the salted password
     */
    private function SetPassword()
    {
        $password = $this->Value('Password');
        if ($password)
        {
            $salt = Str::Start(md5(uniqid(microtime())), 8);
            $pwHash = hash('sha256', $password . $salt);
            $this->member->SetPassword($pwHash);
            $this->member->SetPasswordSalt($salt);
        }
    }
    
     /**
     * True if user group can be assigned
     * @return bool
     */
    protected function CanAssignGroup()      
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->member);
    }
    
    
    private function SaveGroups()
    {
        $exGroupIDs = Membergroup::GetKeyList(MembergroupUtil::MemberMembergroups($this->member));
        $selGroupIDs = Request::PostArray('MemberGroup');
        $this->DeleteOldGroups($selGroupIDs);
        $this->SaveNewGroups($exGroupIDs, $selGroupIDs);
    }
    /**
     * Deletes the unselected group ids
     * @param array $exGroupIDs Currently assigned group ids
     * @param array $selGroupIDs Selected group ids
     */
    private function DeleteOldGroups(array $selGroupIDs)
    {
        $tblMmg = MemberMembergroup::Schema()->Table();
        $sql = Access::SqlBuilder();
        $where = $sql->Equals($tblMmg->Field('Member'), $sql->Value($this->member->GetID()));
        if (count($selGroupIDs) > 0)
        {
            $selectedList = $sql->InListFromValues($selGroupIDs);
            $where = $where->And_($sql->NotIn($tblMmg->Field('MemberGroup'), $selectedList));
        }
        MemberMembergroup::Schema()->Delete($where);
    }
    /**
     * Saves the new groups
     * @param array $exGroupIDs
     * @param array $selGroupIDs
     */
    private function SaveNewGroups(array $exGroupIDs, array $selGroupIDs)
    {
        foreach ($selGroupIDs as $selGroupID)
        {
            if (!in_array($selGroupID, $exGroupIDs))
            {
                $mmg = new MemberMembergroup();
                $mmg->SetMember($this->member);
                $mmg->SetMemberGroup(new Membergroup($selGroupID));
                $mmg->Save();
            }
        }
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the member list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new MemberList());
    }
}

