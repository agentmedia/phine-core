<?php

namespace Phine\Bundles\Core\Logic\Util;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Module\ModuleBase;
use Phine\Bundles\Core\Logic\Module\Enums\ModuleLocation;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Bundle\BundleManifest;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

/**
 * Finds classes by their simplified type string and calculates namespace
 */
class ClassFinder
{
    /**
     * The separator between bundle and module in type names
     * @var string The bundle module separator string
     */
    private static $bundleModuleSeparator = '-';
    static function RootNamespace($bundleName)
    {
        $packageBundles = PathUtil::PackageBundles();
        if (isset($packageBundles[$bundleName])) {
            return "\\Phine";
        } else {
            return "\\App\\Phine";
        }
        
    }
   
    /**
     * The namespace of a bundle
     * @param string $bundleName The name of the bundle
     * @return string Returns the bundle namespace
     */
    static function BundleNamespace($bundleName)
    {
        return self::RootNamespace($bundleName) . '\\Bundles\\' . $bundleName;
    }
    
    /**
     * Gets the class name by type string and module loation
     * @param string $type The (simplified) type string
     * @param ModuleLocation $location The location; either Backend or Frontend
     * @return string Returns the class name associated with the type string
     * @throws \InvalidArgumentException Raises exception if type has incorrect format
     */
    static function ModuleClass($type, ModuleLocation $location)
    {
        $parts = \explode(self::$bundleModuleSeparator, $type);
        if (count($parts) != 2)
        {
            throw new \InvalidArgumentException('Module type string must be in the format <bundle>-<module>');
        }
        $bundle = $parts[0];
        $module = $parts[1];
        $folderLocation = $location->Value();
        return self::BundleNamespace($bundle) . "\\Modules\\$folderLocation\\$module";
    }
    
    /**
     * Finds the module associated with the given type and returns an instance
     * @param string $type The module type
     * @param ModuleLocation $location The location; either backend or frontend
     * @return ModuleBase
     */
    static function CreateModule($type, ModuleLocation $location)
    {
        $class = self::ModuleClass($type, $location);
        if (!class_exists($class))
        {
            return null;
        }
        return new $class();
    }
    
    /**
     * Finds the frontend module associated with the given type and returns an instance
     * @param string $type The module type
     * @return ModuleBase
     */
    static function CreateFrontendModule($type)
    {
        return self::CreateModule($type, ModuleLocation::Frontend());
    }
    
    /**
     * Finds the frontend module associated with the given type and returns an instance
     * @param string $type The module type
     * @return ModuleBase Returns the associated backend module
     */
    static function CreateBackendModule($type)
    {
        return self::CreateModule($type, ModuleLocation::Backend());
    }
    
    /**
     * Calculates the module type by bundle and module name
     * @param ModuleBase $module The module
     * @return string
     */
    static function ModuleType(ModuleBase $module)
    {
        return self::CalcModuleType($module->MyBundle(), $module->MyName());
    }
    
    /**
     * Calculates the module type by bundle and module name
     * @param string $bundle The bundle name
     * @param string $module The module name
     * @return string
     */
    static function CalcModuleType($bundle, $module)
    {
        return \implode(self::$bundleModuleSeparator, array($bundle, $module));
    }
    
    /**
     * Returns the bundle manifest
     * @param string $bundleName
     * @return BundleManifest
     */
    static function Manifest($bundleName)
    {
        $manifestClass = self::BundleNamespace($bundleName) . '\\Manifest' ;
        return new $manifestClass;
    }
    
    /**
     * The modules for the backend navigation
     * @return Returns an array with bundle names as keys and backend modules as list
     */
    static function BackendNavModules()
    {
        $result = array();
        $allBundles = PathUtil::Bundles();
        //force Core to appear first
        $coreKey = array_search('Core', $allBundles);
        unset($allBundles[$coreKey]);
        array_unshift($allBundles, 'Core');
        $bundles = array_values($allBundles);
        
        foreach ($bundles as $bundle)
        {
            $modules = PathUtil::BackendModules($bundle);
           
            foreach ($modules as $module)
            {
                $type = self::CalcModuleType($bundle, $module);
                $instance = self::CreateBackendModule($type);
                
                if (!($instance instanceof BackendModule))
                {
                    continue;
                }
                
                if ($instance->SideNavIndex() >= 0 &&
                        BackendModule::Guard()->Allow(BackendAction::Read(), $instance))
                {
                    self::AddBackendNavModule($result, $instance);
                }
            }
        }
        return self::SortByNavIndex($result);
    }
    
     /**
     * Sorts the the navigation items by index
     */
    private static function SortByNavIndex(array &$result)
    {
        $sorted = array();
        foreach ($result as $bundle=>$modules)
        {
            ksort($modules);
            $sorted[$bundle] = $modules;
        }
        return $sorted;
    }
    
     /**
     * Adds the module to the side navigation
     * @param array &$result The resulting array
     * @param BackendModule $module The backend module
     */
    private static function AddBackendNavModule(array &$result, BackendModule $module)
    {
        $bundle = $module->MyBundle();
        if (!isset($result[$bundle]))
        {
            $result[$bundle] = array();
        }
        $result[$bundle][$module->SideNavIndex()] = $module;
    }
}

