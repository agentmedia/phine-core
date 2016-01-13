<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;

use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Database\Core\Layout;
use Phine\Database\Core\Area;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Database\Access;
use Phine\Framework\System\String;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\Php\Writer;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Database\Core\Usergroup;
use Phine\Bundles\Core\Snippets\BackendRights\LayoutRights;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * A form for layouts
 */
class LayoutForm extends BackendForm
{
    
    /**
     * The layout currently edited
     * @var Layout
     */
    private $layout;
    
    /**
     * The areas
     * @var Area[]
     */
    private $areas;
    
    /**
     * The area names
     * @var string[]
     */
    private $areaNames;
    
    /**
     * The layout rights snippet
     * @var LayoutRights
     */
    protected $layoutRights;
    /**
     * Initializes the layout form
     * @return boolea
     */
    protected function Init()
    {
        $this->layout = new Layout(Request::GetData('layout'));
        $this->layoutRights = new LayoutRights($this->layout->GetUserGroupRights());
        $this->InitAreas();
        $this->AddNameField();
        $this->AddAreasField();
        $this->AddUserGroupField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    
    private function InitAreas()
    {
        $this->areaNames = array();
        $this->areas = array();
        if (!$this->layout->Exists())
        {
            return '';
        }
        
        $sql = Access::SqlBuilder();
        $tbl = Area::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Layout'), $sql->Value($this->layout->GetID()))
            ->And_($sql->IsNull($tbl->Field('Previous')));
        
        $area = Area::Schema()->First($where);
        while ($area)
        {
            $this->areaNames[] = $area->GetName();
            $this->areas[] = $area;
            $area = Area::Schema()->ByPrevious($area);
        }
    }
    
    /**
     * Adds the user group field
     */
    private function AddUserGroupField()
    {
        $name = 'UserGroup';
        $field = new Select($name, '');
        
        $field->AddOption('', Trans('Core.LayoutForm.NoGroup'));
        if ($this->layout->Exists() && $this->layout->GetUserGroup())
        {
            $field->SetValue($this->layout->GetUserGroup()->GetID());
        }
        DBSelectUtil::AddUserGroupOptions($field);
        $this->AddField($field);
    }
    
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->layout->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueField($this->layout, $name));
    }
    
    private function AreaNameExists($name)
    {
        $sql = Access::SqlBuilder();
        $tblArea = Area::Schema()->Table();
        $where = $sql->Equals($tblArea->Field('Layout'), $sql->Value($this->layout->GetID()))
                ->And_($sql->Equals($tblArea->Field('Name'), $sql->Value($name)));
        
        return Area::Schema()->Count(false, $where) > 0;
    }
    
    /**
     * Adds the server path field to the form
     */
    private function AddAreasField()
    {
        $name = 'Areas';
        $field = Input::Text($name, join(', ' , $this->areaNames));
        $this->AddField($field);
        if ($this->layout->Exists())
        {
            $field->SetHtmlAttribute('readonly', 'readonly');
        }
        else
        {
            $this->SetTransAttribute($name, 'placeholder');
            $this->SetRequired($name);
        }
    }
    
    /**
     * Saves the layout
     */
    protected function OnSuccess()
    {
        $action = Action::Update();
        $isNew = !$this->layout->Exists();
        if ($isNew)
        {
            $action = Action::Create();
            $this->layout->SetUser(self::Guard()->GetUser());
        }
        $oldFile = $isNew ? '' : PathUtil::LayoutTemplate($this->layout);
        $this->layout->SetName($this->Value('Name'));
        $this->layout->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportLayoutAction($this->layout, $action);
        if ($this->CanAssignGroup())
        {
            $this->SaveRights();
        }
        if ($isNew)
        {
            $this->SaveAreas();
        }
        $this->UpdateFiles($oldFile);
        $args = array('layout'=>$this->layout->GetID());
        Response::Redirect(BackendRouter::ModuleUrl(new AreaList(), $args));
    }
    
    /**
     * True if user group can be assigned
     * @return bool
     */
    protected function CanAssignGroup()      
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->layout);
    }
    
    private function SaveAreas()
    {
        $names = explode(',', $this->Value('Areas'));
        $prev = null;
        foreach ($names as $name)
        {
            $name = String::Trim($name);
            if (!$this->AreaNameExists($name))
            {
                $area = new Area();
                $area->SetPrevious($prev);
                $area->SetLayout($this->layout);
                $area->SetName($name);
                $area->Save();
                $prev = $area;
            }
        }
    }
    
    private function SaveRights()
    {
        $groupID = $this->Value('UserGroup');
        $userGroup = Usergroup::Schema()->ByID($groupID);
        $this->layout->SetUserGroup($userGroup);
        if (!$userGroup)
        {
            $oldRights = $this->layout->GetUserGroupRights();
            if ($oldRights)
            {
                $oldRights->GetContentRights()->Delete();
            }
            $this->layout->SetUserGroupRights(null);
        }
        else
        {
            $this->layout->SetUserGroup($userGroup);
            $this->layoutRights->Save();
            $this->layout->SetUserGroupRights($this->layoutRights->Rights());
        }
        $this->layout->Save();
    }
    
    
    private function UpdateFiles($oldFile)
    {
        $newFile = PathUtil::LayoutTemplate($this->layout);
        if ($oldFile == $newFile)
        {
            return;
        }
        else if ($oldFile)
        {
            File::Move($oldFile, $newFile);
        }
        else
        {
            File::CreateWithText($newFile, $this->TemplateContent());
        }
    }
    
    
    private function TemplateContent()
    {
        $areaCodes = '';
        $writer = new Writer();
        $this->InitAreas();
        
        $template = PathUtil::CodeTemplate($this->MyBundle(), 'Layout.phtml');
        $templateCode = File::GetContents($template);
        $indent = $this->GetIndent($templateCode, '_{areas}_');
        
        for ($idx = 0; $idx < count($this->areaNames); ++$idx)
        {
            $name = $this->areaNames[$idx];
            $writer->StartPhpInline();
            $writer->AddCommandInline('echo $this->RenderArea(\''. $name .'\')');
            if ($idx < count($this->areaNames) -1)
            {
                $writer->EndPhp();
            }
            else
            {
                $writer->EndPhpInline();
            }
            if ($idx > 0)
            {
                $areaCodes .= $indent;
            }
            $areaCodes .= $writer->Flush();
        }
        return str_replace('_{areas}_', $areaCodes, $templateCode);
    }
    
    private function GetIndent($content, $placeholder)
    {
        $spaces = array();
        $pos = (int)strpos($content, $placeholder);
        $pos -= 1;
        
        while ($pos >= 0 && strpos(" \t", $content{$pos}) !== false)
        {
            $spaces[] = $content{$pos};
            --$pos;
        }
        return join('', array_reverse($spaces));
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the layout list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new LayoutList());
    }
}

