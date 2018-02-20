<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\Validation\DatabaseCount;

use App\Phine\Database\Access;
use App\Phine\Database\Core\Layout;
use App\Phine\Database\Core\Area;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Tree\TreeBuilder;
use Phine\Bundles\Core\Logic\Tree\AreaListProvider;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The area form
 */
class AreaForm extends BackendForm
{
    
    /**
     * The layout currently edited
     * @var Layout
     */
    private $layout;
    
    /**
     * The edited area
     * @var Area
     */
    private $area;
    
    /**
     * Initializes the form
     * @return boolean
     */
    protected function Init()
    {        
        $this->area = new Area(Request::GetData('area'));   
        $this->layout= $this->area->Exists() ? $this->area->GetLayout() : 
            new Layout(Request::GetData('layout'));
        
        if (!$this->area->Exists() && !$this->layout->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new LayoutList()));
            return true;
        }
        $this->AddNameField();
        $this->AddLockedField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    
    /**
     * Adds name field to the form
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->area->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueFieldAnd($this->area, $name, $this->LayoutCondition()));
    }
    
    /**
     * Adds the locked field
     */
    private function AddLockedField()
    {
        $name = 'Locked';
        $field = new Checkbox($name);
        if ($this->area->Exists() && $this->area->GetLocked())
        {
            $field->SetChecked();
        }
        $this->AddField($field);
    }
    
    /**
     * Gets the layout condition for the name validator
     * @return \Phine\Framework\Database\Sql\Condition
     */
    private function LayoutCondition()
    {
        $sql = Access::SqlBuilder();
        $tbl = Area::Schema()->Table();
        return $sql->Equals($tbl->Field('Layout'), $sql->Value($this->layout->GetID()));
    }
    
    
    
    /**
     * Saves the area
     */
    protected function OnSuccess()
    {
        $this->area->SetName($this->Value('Name'));
        $this->area->SetLocked((bool)$this->Value('Locked'));
        $action = Action::Update();
        if (!$this->area->Exists())
        {
            $action = Action::Create();
            $this->SaveNew();
        }
        else
        {
            $this->area->Save();
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportAreaAction($this->area, $action);
        $args = array('layout' => $this->layout->GetID());
        Response::Redirect(BackendRouter::ModuleUrl(new AreaList(), $args));
    }
    
    private function SaveNew()
    {
        $this->area->SetLayout($this->layout);
        $previous = Area::Schema()->ByID(Request::GetData('previous'));
        $treeBuilder = new TreeBuilder(new AreaListProvider($this->layout));
        $treeBuilder->Insert($this->area, null, $previous);
    }
    
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new AreaList(), array('layout'=>$this->layout->GetID()));
    }
}