<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Database\Core\Usergroup;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The frontend the module selection form
 */
class UsergroupForm extends BackendForm
{
    
    /**
     * The usergroup currently edited
     * @var Usergroup
     */
    private $group;
    
    protected function Init()
    {
        $this->group = new Usergroup(Request::GetData('usergroup'));
        $this->AddNameField();
        $this->AddCreateLayoutsField();
        $this->AddCreateContainersField();
        $this->AddCreateSitesField();
        $this->AddSubmit();
        return parent::Init();
    }
    /**
     * Adds the group name field
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->group->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueField($this->group, $name));
    }
    
    
    /**
     * Adds the create sites (right) checkbox
     */
    private function AddCreateSitesField()
    {
        $name = 'CreateSites';
        $this->AddField(new Checkbox($name, $name, (bool)$this->group->GetCreateSites()));
    }
    
    /**
     * Adds the create layouts (right) checkbox
     */
    private function AddCreateLayoutsField()
    {
        $name = 'CreateLayouts';
        $this->AddField(new Checkbox($name, $name, (bool)$this->group->GetCreateLayouts()));
    }
    
    /**
     * Adds he create containers (right) field
     */
    private function AddCreateContainersField()
    {
        $name = 'CreateContainers';
        $this->AddField(new Checkbox($name, $name, (bool)$this->group->GetCreateContainers()));
    }
    
    /**
     * Saves the group and redirects to the list
     */
    protected function OnSuccess()
    {
        $action = $this->group->Exists() ? Action::Update() : Action::Create();
        $this->group->SetName($this->Value('Name'));
        $this->group->SetCreateContainers((bool)$this->Value('CreateContainers'));
        $this->group->SetCreateLayouts((bool)$this->Value('CreateLayouts'));
        $this->group->SetCreateContainers((bool)$this->Value('CreateContainers'));
        $this->group->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportUserGroupAction($this->group, $action);
        $target = BackendRouter::ModuleUrl(new UsergroupList());
        Response::Redirect($target);
    }

}

