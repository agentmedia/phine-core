<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use Phine\Framework\System\IO\Folder;
use Phine\Framework\System\IO\Path;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Framework\System\IO\File;
use App\Phine\Database\Core\Content;
use App\Phine\Database\Access;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class TemplateList extends BackendModule
{
    protected $bundles;
    /**
     * Removes the template if necessary
     */
    protected function BeforeInit()
    {
        if ($this->RemoveTemplate())
        {
            Response::Redirect(Request::Uri());
            return true;
        }
        parent::BeforeInit();
    }
    /**
     * Initializes the template list
     * @return boolean Returns false if anything is OK
     */
    protected function Init()
    {
        $this->InitBundles();
        return parent::Init();
    }
    /**
     * Initializes the bundle names by fetching those containing modules with adjustable templates
     */
    protected function InitBundles()
    {
        $bundles = PathUtil::Bundles();
        $this->bundles = array();
        foreach ($bundles as $bundle)
        {
            if (count($this->BundleModules($bundle)) > 0)
            {
                $this->bundles[] = $bundle;
            }
        }
    }
    
    /**
     * 
     * @param string $bundleName The name of the bundle
     * @return FrontendModule[] Returns all bundle modules allowing custom templates
     */
    protected function BundleModules($bundleName)
    {
        $moduleNames = PathUtil::FrontendModules($bundleName);
        $result = array();
        foreach ($moduleNames as $moduleName)
        {
            $type = ClassFinder::CalcModuleType($bundleName, $moduleName);
            $module = ClassFinder::CreateFrontendModule($type);
            if ($module instanceof FrontendModule && $module->AllowCustomTemplates())
            {
                $result[] = $module;
            }
        }
        return $result;
    }
    
    
    /**
     * Gets the module templates
     * @param FrontendModule $module The frontend module
     * @return string[] Returns the template files 
     */
    protected function ModuleTemplates($module)
    {
        $folder = PathUtil::ModuleCustomTemplatesFolder($module);
        if (!Folder::Exists($folder))
        {
            return array();
        }
        $templates = Folder::GetFiles($folder);
        $result = array();
        foreach ($templates as $template)
        {
            if (Path::Extension($template) == 'phtml')
            {
                $result[] = Path::RemoveExtension($template);
            }
        }
        return $result;
    }
    
    /**
     * The position in the side navigation
     * @return int Returns the position index in the side navigation
     */
    public function SideNavIndex()
    {
        return 6;
    }
    /**
     * The removel template module
     * @param array $idParts
     * @return FrontendModule
     */
    protected function RemovalTemplateModule(array $idParts)
    {
        if (count($idParts) != 2)
        {
            return null;
        }
        $module = ClassFinder::CreateFrontendModule($idParts[0]);
        return ($module instanceof FrontendModule && $module->AllowCustomTemplates()) ? $module : null;
    }
    private function RemoveTemplate()
    {
        $id = Request::PostData('delete');   
        if (!$id)
        {
            return false;
        }
        $idParts = \explode('/', $id);
        $module = $this->RemovalTemplateModule($idParts);
        $templateName = trim($idParts[1]);
        if (!$module || !$templateName)
        {
            return false;
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportTemplateAction($module->MyType(), $templateName, Action::Delete());
        $folder = PathUtil::ModuleCustomTemplatesFolder($module);
        $template = Path::Combine($folder, Path::AddExtension($templateName, 'phtml'));
        if (File::Exists($template))
        {
            File::Delete($template);
        }
        $this->DeleteUsages($module, $templateName);
        return true;
    }
    
    protected function FormUrl(FrontendModule $module, $template = '')
    {   
        return BackendRouter::ModuleUrl(new TemplateForm(), array('template'=>$this->TemplateID($module, $template)));
    }
    
    protected function TemplateID(FrontendModule $module, $template = '')
    {
        if ($template)
        {
            return join('/', array($module->MyType(), $template));
        }
        return $module->MyType();
    }
    
    private function DeleteUsages(FrontendModule $module, $templateName)
    {
        $sql = Access::SqlBuilder();
        $tblContent = Content::Schema()->Table();
        $where = $sql->Equals($tblContent->Field('Type'), $sql->Value($module->MyType()))
                ->And_($sql->Equals($tblContent->Field('Template'), $sql->Value($templateName)));
        $setList = $sql->SetList('Template', $sql->Value(''));
        Content::Schema()->Update($setList, $where);
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


