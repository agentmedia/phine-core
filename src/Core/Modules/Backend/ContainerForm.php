<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;

use Phine\Bundles\Core\Logic\Module\BackendForm;
use App\Phine\Database\Core\Container;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Bundles\Core\Snippets\BackendRights\ContainerRights;
use Phine\Framework\FormElements\Fields\Select;
use App\Phine\Database\Core\Usergroup;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;
/**
 * A form for container
 */
class ContainerForm extends BackendForm
{
    
    /**
     * The container currently edited
     * @var Container
     */
    private $container;
    
    /**
     * The layout rights snippet
     * @var ContainerRights
     */
    protected $containerRights;
    /**
     * Initializes the container form
     * @return boolea True if processing shall continue
     */
    protected function Init()
    {
        $this->container = new Container(Request::GetData('container'));
        $this->containerRights = new ContainerRights($this->container->GetUserGroupRights());
        $this->AddNameField();
        $this->AddUserGroupField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->container->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueField($this->container, $name));
    }
     /**
     * Adds the user group field
     */
    private function AddUserGroupField()
    {
        $name = 'UserGroup';
        $field = new Select($name, '');
        
        $field->AddOption('', Trans('Core.ContainerForm.NoGroup'));
        if ($this->container->Exists() && $this->container->GetUserGroup())
        {
            $field->SetValue($this->container->GetUserGroup()->GetID());
        }
        DBSelectUtil::AddUserGroupOptions($field);
        $this->AddField($field);
    }
    /**
     * Saves the container
     */
    protected function OnSuccess()
    {
        $action = Action::Update();
        if (!$this->container->Exists())
        {
            $action = Action::Create();
            $this->container->SetUser(self::Guard()->GetUser());
        }
        $this->container->SetName($this->Value('Name'));
        $this->container->Save();
        
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportContainerAction($this->container, $action);
        
        if ($this->CanAssignGroup())
        {
            $this->SaveRights();
        }
        Response::Redirect(BackendRouter::ModuleUrl(new ContainerList()));
    }
    
    
    /**
     * True if groups can be assigned
     * @return boolean
     */
    protected function CanAssignGroup()
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->container);
    }
    
    /**
     * Saves group and rights
     */
    private function SaveRights()
    {
        $groupID = $this->Value('UserGroup');
        $userGroup = Usergroup::Schema()->ByID($groupID);
        $this->container->SetUserGroup($userGroup);
        if (!$userGroup)
        {
            $oldRights = $this->container->GetUserGroupRights();
            if ($oldRights)
            {
                $oldRights->GetContentRights()->Delete();
            }
            $this->container->SetUserGroupRights(null);
        }
        else
        {
            $this->container->SetUserGroup($userGroup);
            $this->containerRights->Save();
            $this->container->SetUserGroupRights($this->containerRights->Rights());
        }
        $this->container->Save();
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the container list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new ContainerList());
    }
}

