<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\ContentForm;
use App\Phine\Database\Core\Container;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
/**
 * The module selection form
 */
class ModuleForm extends ContentForm
{
    protected function AutoPublish()
    {
        return $this->FrontendModule()->ContentForm() === null;
    }
    
    private function AddModulesField()
    {
        $name = 'Module';
        $select = new Select($name, '');
        $select->AddOption('', Trans('Core.PleaseSelect'));
        $this->AddField($select);
        $this->SetRequired($name);
        
        foreach (PathUtil::Bundles() as $bundle)
        {
            $modules = PathUtil::FrontendModules($bundle);
            foreach ($modules as $moduleName)
            {
                $type = ClassFinder::CalcModuleType($bundle, $moduleName);
                $module = ClassFinder::CreateFrontendModule($type);
                if ($module instanceof FrontendModule && 
                        self::Guard()->Allow(BackendAction::UseIt(), $module->ContentForm()))
                {
                    $this->AddModuleTypeOption($select, $type);
                }
               //$select->AddOption($type, Trans($type));
            }
        }
    }
    /**
     * Adds the module type option, if allowed
     * @param Select $select The select box
     * @param string $type The module type
     */
    private function AddModuleTypeOption(Select $select, $type)
    {
        if ($type != 'BuiltIn-Container'  ||
                (!Request::GetData('container') && Container::Schema()->Count() > 0))
        {
            $select->AddOption($type, Trans($type));
        }
    }
    
    protected function BeforeSave()
    {
        $contentForm = $this->FrontendModule()->ContentForm();
        if ($contentForm)
        {
            $this->DoRedirect($contentForm);
            return true;
        }
        return false;
    }
    
    private function DoRedirect(ContentForm $contentForm)
    {
        $params = Request::GetArray();
        Response::Redirect(BackendRouter::ModuleUrl($contentForm, $params));
    }
    

    protected function ElementSchema()
    {
        return null;
    }

    /**
     *
     * @var FrontendModule
     */
    private $frontendModule;
    
    /**
     * @return FrontendModule
     */
    protected function FrontendModule()
    {
        if (!$this->frontendModule)
        {
            $this->frontendModule = ClassFinder::CreateFrontendModule($this->Value('Module'));
        }
        return $this->frontendModule;
    }

    protected function InitForm()
    {
        $this->AddModulesField();
        $this->AddSubmit();
    }

    protected function SaveElement()
    {
        return null;
    }

}

