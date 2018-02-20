<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Framework\System\IO\Path;
use Phine\Framework\Validation;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use App\Phine\Database\Access;
use App\Phine\Database\Core\Content;
use Phine\Framework\FormElements\Fields\Textarea;
use Phine\Framework\System\IO\Folder;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The form for the template
 */
class TemplateForm extends BackendForm
{
    
    /**
     * The template name
     * @var string
     */
    protected $template;
    
    /**
     * The module name
     * @var \Phine\Bundles\Core\Logic\Module\FrontendModule
     */
    protected $module;
    
    
    
    /**
     * The template folder
     * @var string
     */
    protected $folder = '';
    
    protected function BeforeInit()
    {
        $templateID = Request::GetData('template');
        $idParts = explode('/', $templateID);
        $this->InitModule($idParts);
        $this->folder = PathUtil::ModuleCustomTemplatesFolder($this->module);
        $this->template = isset($idParts[1]) ? $idParts[1] : '';
        
        return !($this->module);
    }
    
    
    /**
     * Initializes the bundle and module names
     * @param array $idParts
     */
    private function InitModule(array $idParts)
    {
        if (count($idParts) < 1 || count($idParts) > 2)
        {
            //TODO: Message
            Response::Redirect(BackendRouter::ModuleUrl(new TemplateList()));
        }
        $this->module = ClassFinder::CreateFrontendModule($idParts[0]);
        if (!$this->module instanceof FrontendModule || !$this->module->AllowCustomTemplates())
        {
            //TODO: Message
            Response::Redirect(BackendRouter::ModuleUrl(new TemplateList()));
        }
    }
    
    /**
     * 
     * @return bool
     */
    protected function Init()
    {
        $this->AddNameField();
        $this->AddContentsField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->template));
        $nameCheck = Validation\RegExp::LettersNumbers("-\._");
        $this->AddValidator($name, $nameCheck);
        $fileCheck = new Validation\FileExists($this->folder, $this->template, 'phtml');
        $this->AddValidator($name, $fileCheck);
        $this->SetRequired($name);
    }
    
    private function AddContentsField()
    {
        $name = 'Contents';
        $value = '';
        if ($this->template)
        {
            $file = $this->CalcFile($this->template);
            $value = File::Exists($file) ? File::GetContents($file) : '';
        }
        else
        {
            $value = File::GetContents($this->module->BuiltInTemplateFile());
        }
        $this->AddField(new Textarea($name, $value));
    }
    
    /**
     * Saves the template into the given file name
     */
    protected function OnSuccess()
    {
        $newTemplate = $this->Value('Name');
        $action = Action::Create();
        if ($this->template)
        {
            $action = Action::Update();
            $this->UpdateUsages($newTemplate);
            $oldFile = $this->CalcFile($this->template);
            if (File::Exists($oldFile))
            {
                File::Delete($oldFile);
            }
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportTemplateAction($this->module->MyType(), $newTemplate, $action);
        if (!Folder::Exists($this->folder))
        {
            Folder::Create($this->folder);
        }
        File::CreateWithText($this->CalcFile($newTemplate), $this->Value('Contents', false));
        Response::Redirect($this->BackLink());
    }
    
    private function CalcFile($template)
    {
        return Path::AddExtension(Path::Combine($this->folder, $template), 'phtml');
    }
    
    protected function UpdateUsages($newTemplate)
    {
        $sql = Access::SqlBuilder();
        $tblContent = Content::Schema()->Table();
        $where = $sql->Equals($tblContent->Field('Template'), $sql->Value($this->template))
                ->And_($sql->Equals($tblContent->Field('Type'), $sql->Value($this->module->MyType())));
        $setList = $sql->SetList('Template', $sql->Value($newTemplate));
        Content::Schema()->Update($setList, $where);
    }
    
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new TemplateList());
    }

}

