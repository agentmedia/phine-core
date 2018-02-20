<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use App\Phine\Database\Core\Membergroup;

/**
 * The member group form
 */
class MembergroupForm extends BackendForm
{
    
    /**
     * The member group currently edited
     * @var Membergroup
     */
    private $group;
   
    /**
     * Initializes the member form
     * @return boolean Returns false to continue processing
     */
    protected function Init()
    {
        $this->group = new Membergroup(Request::GetData('membergroup'));
        $this->AddNameField();
        $this->AddSubmit();
        return parent::Init();
    }
    /**
     * Adds the name input field
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->group->GetName()));
        $this->SetRequired($name);   
        $this->AddValidator($name, DatabaseCount::UniqueField($this->group, $name));
    }
    
    
    /**
     * Saves the group
     */
    protected function OnSuccess()
    {
        $this->group->SetName($this->Value('Name'));
        $this->group->Save();
        Response::Redirect(BackendRouter::ModuleUrl(new MembergroupList()));
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the member list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new MembergroupList());
    }
}

