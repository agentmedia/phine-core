<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Database\Core\Usergroup;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Database\Access;
use Phine\Database\Core\ModuleLock;
use Phine\Bundles\Core\Logic\Module\BackendModule;

use Phine\Bundles\Core\Snippets\FormParts\FieldColumnizer;
/**
 * The module lock form
 */
class ModuleLockForm extends BackendForm
{
    /**
     *
     * @var Usergroup
     */
    protected $group;
    
    /**
     *
     * @var FieldColumnizer[]
     */
    private $fieldsets;
    
    protected function BeforeInit()
    {
        $this->group = Usergroup::Schema()->ByID(Request::GetData('usergroup'));
        if (!$this->group || !self::Guard()->GetUser()->GetIsAdmin())
        {
            //TODO: Message
            Response::Redirect(BackendRouter::ModuleUrl(new Overview()));
        }
        return parent::BeforeInit();
    }
    
    protected function Init()
    {
        $this->AddModules();
        $this->AddSubmit();
        return parent::Init();
    }
    
    private function AddModules()
    {
        $bundles = $this->Bundles();
        foreach ($bundles as $bundle)
        {
            $this->AddField(new Checkbox($bundle, 1, $this->HasLock($bundle)),
                    false, $bundle);
            $columnizer = new FieldColumnizer($this, 3);
            
            $modules = $this->Modules($bundle);
            
            foreach ($modules as $module)
            {
                $this->AddField(new Checkbox($this->FieldName($bundle, $module), 1, $this->HasLock($bundle, $module)), false,
                        $module);
                $columnizer->AddField($this->FieldName($bundle, $module));
            }
            $this->fieldsets[$bundle] = $columnizer;
        }
    }
    /**
     * Renders all checkboxes for the modules
     * @param string $bundle
     * @return string
     */
    protected function RenderModuleCheckBoxes($bundle)
    {
        $columnizer = $this->fieldsets[$bundle];
        return $columnizer->Render();
    }
    
    protected function FieldName($bundle, $module)
    {
        return ClassFinder::CalcModuleType($bundle, $module);;
    }
    
    /**
     * Gets all bundles containing backend modules
     * @return string[] Returns all bundle names
     */
    protected function Bundles()
    {
        $bundles = PathUtil::Bundles();
        $result = array();
        foreach ($bundles as $bundle)
        {
            if (count($this->Modules($bundle)) > 0)
            {
                $result[] = $bundle;
            }
        }
        return $result;
    }
    
    /**
     * Gets all backend module names for a bundle
     * @param string $bundle The bundle name
     * @return string Returns the module names
     */
    protected function Modules($bundle)
    {
        $modules = PathUtil::BackendModules($bundle);
        $result = array();
        foreach ($modules as $module)
        {
            $instance = ClassFinder::CreateBackendModule(ClassFinder::CalcModuleType($bundle, $module));
            if ($instance instanceof BackendModule)
            {
                $result[] = $module;
            }
        }
        return $result;
    }
    
    protected function OnSuccess()
    {
        $this->DeleteUnselected();
        $bundles = $this->Bundles();
        foreach ($bundles as $bundle)
        {
            if ($this->Value($bundle))
            {
                $this->SaveLock($bundle);
                continue;
            }
            $modules =$this->Modules($bundle);
            foreach ($modules as $module)
            {
                if ($this->Value($this->FieldName($bundle, $module)))
                {
                    $this->SaveLock($bundle, $module);
                }
            }
        }
        Response::Redirect(BackendRouter::ModuleUrl(new UsergroupList()));
    }
    
    private function DeleteUnselected()
    {
        $locks = ModuleLock::Schema()->FetchByUserGroup(false, $this->group);
        foreach ($locks as $lock)
        {
            if ($lock->GetModule() == '' && !$this->Value($lock->GetBundle()))
            {
                $lock->Delete();
            }
            else if ($this->Value($lock->GetBundle())|| 
                    !$this->Value($this->FieldName($lock->GetBundle(), $lock->GetModule())))
            {
                $lock->Delete();
            }
        }
    }
    
    
    private function SaveLock($bundle, $module = '')
    {
        if ($this->HasLock($bundle, $module))
        {
            return;
        }
        $modLock = new ModuleLock();
        $modLock->SetUserGroup($this->group);
        $modLock->SetBundle($bundle);
        $modLock->SetModule($module);
        $modLock->Save();
    }
    
    /**
     * True if a lock with given bundle and module are set
     * @param string $bundle The bundle name
     * @param string $module The module name
     * @return boolean Returns true if the lock is set
     */
    private function HasLock($bundle, $module = '')
    {
        $sql = Access::SqlBuilder();
        $tblModLock = ModuleLock::Schema()->Table();
        
        $where = $sql->Equals($tblModLock->Field('Bundle'), $sql->Value($bundle))
                    ->And_($sql->Equals($tblModLock->Field('Module'), $sql->Value($module)))
                    ->And_($sql->Equals($tblModLock->Field('UserGroup'), $sql->Value($this->group->GetID())));
        
        return ModuleLock::Schema()->Count(false, $where) > 0;
    }
}