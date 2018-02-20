<?php

namespace Phine\Bundles\Core\Logic\Bundle;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\File;
use App\Phine\Database\Core\InstalledBundle;
use App\Phine\Database\Core\Site;

abstract class BundleManifest
{
    /**
     * Names of all loaded bundles
     * @var string[]
     */
    static $_loadedBundles = array();
    /**
     * The nice title of the bundle
     */
    final function Title()
    {
        return Trans(self::BundleName() . '.Manifest.Title');
    }
    
    /**
     * The bundle description
     */
    final function Description()
    {
        return Trans(self::BundleName() . '.Manifest.Description');
    }
    
    /**
     * The bundle version
     */
    abstract function Version();
    
    /**
     * Load code to the backend that is not accessible by phine autoload
     */
    protected abstract function LoadBackendCode();
    
    
    /**
     * Load code to the backend that is not accessible by phine autoload
     */
    protected abstract function LoadFrontendCode();
    
    
    /**
     * Loads the bundle with all dependencies
     */
    final function LoadToBackend()
    {
        foreach ($this->Dependencies() as $depedency)
        {
            $bundleName = $depedency->BundleName();
            if (!in_array($bundleName, self::$_loadedBundles))
            {
                $depManifest = ClassFinder::Manifest($bundleName);
                $depManifest->LoadToBackend();
            }
        }
        $this->LoadBackendTranslations();
        $this->LoadBackendCode();
        self::$_loadedBundles[] = static::BundleName();
    }
    /**
     * Loads the bundle code and translations to the f
     * @param Site $site
     */
    protected function LoadToFrontend(Site $site)
    {
        foreach ($this->Dependencies() as $depedency)
        {
            $bundleName = $depedency->BundleName();
            if (!in_array($bundleName, self::$_loadedBundles))
            {
                $depManifest = ClassFinder::Manifest($bundleName);
                $depManifest->LoadToFrontend($site);
            }
        }
        $this->LoadFrontendTranslations($site);
        $this->LoadFrontendCode();
        self::$_loadedBundles[] = static::BundleName();
    }
    /**
     * Loads all installed bundles to the backend
     */
    static function LoadInstalledToBackend()
    {
        $bundles = InstalledBundle::Schema()->Fetch();
        foreach ($bundles as $bundle)
        {
            $manifest = ClassFinder::Manifest($bundle->GetBundle());
            $manifest->LoadToBackend();
        }
    }
    
    /**
     * Loads installed bundles to the frontend
     * @param Site $site
     */
    static function LoadInstalledToFrontend(Site $site)
    {
        $bundles = InstalledBundle::Schema()->Fetch();
        foreach ($bundles as $bundle)
        {
            $manifest = ClassFinder::Manifest($bundle->GetBundle());
            $manifest->LoadToFrontend($site);
        }
    }
    
    /**
     * Loads backend translations of the bundle
     */
    private function LoadBackendTranslations()
    {
        $backendUser = BackendModule::Guard()->GetUser();
        if ($backendUser)
        {
            $backendLanguage = $backendUser->GetLanguage()->GetCode();
            $backendTranslations = PathUtil::BackendBundleTranslationFile($this->BundleName(), $backendLanguage);
            if (File::Exists($backendTranslations))
            {
                require_once $backendTranslations;
            }
        }
    }
    
      /**
     * Loads backend translations of the bundle
     */
    private function LoadFrontendTranslations(Site $site)
    {
        $language = $site->GetLanguage()->GetCode();
        $frontendTranslations = PathUtil::FrontendBundleTranslationFile($this->BundleName(), $language);
        if (File::Exists($frontendTranslations))
        {
            require_once $frontendTranslations;
        }
        
    }
    
    /**
     * Gets the loaded bundles
     * @return string[] Returns the names of all loaded bundles
     */
    final static function LoadedBundles()
    {
        return self::$_loadedBundles;
    }
    final static function BundleName()
    {
        $manifest = new static();
        $class = new \ReflectionClass($manifest);
        $namespace = $class->getNamespaceName();
        $parts = explode('\\', $namespace);
        return $parts[count($parts) - 1];
    }
    
    abstract function Manufacturer();
    /**
     * The bundles this bundle depends on; for overriding in derived classes
     * @return BundleDependency[]
     */
    abstract function Dependencies();
}
