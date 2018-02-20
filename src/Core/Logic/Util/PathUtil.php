<?php
namespace Phine\Bundles\Core\Logic\Util;
use Phine\Framework\System\IO\Path;
use Phine\Bundles\Core\Logic\Module\ModuleBase;
use Phine\Bundles\Core\Logic\Module\TemplateModule;

use Phine\Framework\System\Str;
use App\Phine\Database\Core\Layout;
use Phine\Framework\System\IO\Folder;
use Phine\Bundles\Core\Logic\Module\Enums\ModuleLocation;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use App\Phine\Database\Core\Site;
use Phine\Framework\System\IO\File;


/**
 * Helper class for calculation of required cms paths
 */
class PathUtil
{
    /**
     * Gets the folder for the bundle
     * @param string $bundleName The name of the bundle
     * @return string Returns the bundle directory path
     */
    static function BundleFolder($bundleName)
    {
        $packageBundles = self::PackageBundles();
        if (isset($packageBundles[$bundleName])) {
            return $packageBundles[$bundleName];
        }
        return Path::Combine(self::AppBundlesFolder(), $bundleName);
    }
    
    static function InstallationFolder($bundleName)
    {
        return Path::Combine(self::BundleFolder($bundleName), 'Installation');
    }
    
    /**
     * Gets the installation sql folder
     * @param string $bundleName The bundle name
     * @return string Returns the base folder for the sql scripts
     */
    static function InstallationSqlFolder($bundleName)
    {
        return Path::Combine(self::InstallationFolder($bundleName), 'SQL');
    }
    
    
    /**
     * Gets the bundles folder
     * @return string Returns the root folder of all bundles
     */
    static function AppBundlesFolder()
    {
        return Path::Combine(PHINE_PATH, 'App/Phine/Bundles');
    }
    
    /**
     * Gets all bundles
     * @return string[] The bundle names
     */
    static function Bundles()
    {
       return array_merge(array_keys(self::PackageBundles()), Folder::GetSubFolders(self::AppBundlesFolder()));
    }
    
    /**
     *
     * @var array
     */
    private static $packageBundles  = null;
    public static function PackageBundles() {
        if (self::$packageBundles !== null) {
            return self::$packageBundles;
        }
        self::$packageBundles = array();
        $cacheFile = self::PackageBundleCacheFile();
        if (File::Exists($cacheFile)) {
            self::$packageBundles = json_decode(File::GetContents($cacheFile), true);
        }
        return self::$packageBundles;
    }
    
    static function PackageBundleCacheFile()
    {
        return Path::Combine(PHINE_PATH, 'App/Phine/Cache/Bundles/packages.json');
    }
    
    
    
    /**
     * Gets all modules in a bundle
     * @param string $bundleName
     * @param ModuleLocation $location Backend or frontend
     * @return string[] Returns the module names
     */
    static function Modules($bundleName, ModuleLocation $location)
    {
        $result = array();
        $modulesFolder = Path::Combine(self::BundleFolder($bundleName), 'Modules');
        $folder = Path::Combine($modulesFolder, (string)$location);
        $files = Folder::GetFiles($folder);
        foreach ($files as $file)
        {
            $result[] = Path::RemoveExtension($file);
        }
        return $result;
    }
    
    /**
     * Gets the backend modules in the bundle
     * @param string $bundleName
     * @return string[] Returns the names of the backend modules
     */
    static function BackendModules($bundleName)
    {
        return self::Modules($bundleName, ModuleLocation::Backend());
    }
    
    /**
     * Gets the frontend module names in a bundle
     * @param string $bundleName The bundle name
     * @return string[] The names of the frontend modules in the bundle
     */
    static function FrontendModules($bundleName)
    {
        return self::Modules($bundleName, ModuleLocation::Frontend());
    }
    
    /**
     * 
     * @param string $bundleName
     * @param string $lang
     * @param ModuleLocation $location
     * @return string
     */
    static function BundleTranslationsFileByLocation($bundleName, $lang, ModuleLocation $location)
    {
        $bundleFolder = self::BundleFolder($bundleName);
        $transFolder = Path::Combine($bundleFolder, 'Translations');
        $locationFolder = Path::Combine($transFolder, (string)$location);
        $langFile = Path::Combine($locationFolder, $lang);
        return Path::AddExtension($langFile, 'php');
    }
    
     /**
     * Returns the frontend bundle translations file for the 
     * @param string $bundleName
     * @param string $lang
     * @param ModuleLocation $location
     * @return string
     */
    static function FrontendBundleTranslationFile($bundleName, $lang)
    {
        return self::BundleTranslationsFileByLocation($bundleName, $lang, ModuleLocation::Frontend());
    }
    
    
     /**
     * Returns the backend bundle translations file for the 
     * @param string $bundleName
     * @param string $lang
     * @param ModuleLocation $location
     * @return string
     */
    static function BackendBundleTranslationFile($bundleName, $lang)
    {
        return self::BundleTranslationsFileByLocation($bundleName, $lang, ModuleLocation::Backend());
    }
    /**
     * Gets the bundle translations file
     * @param ModuleBase $module
     * @param string $lang
     * @return string Returns the bundle translations file
     */
    static function BundleTranslationsFile(ModuleBase $module, $lang)
    {
        $moduleTransFolder = self::ModuleTranslationsFolder($module);
        $bundleTransFolder = Path::Directory($moduleTransFolder);
        $file = Path::Combine($bundleTransFolder, $lang);
        return Path::AddExtension($file, 'php');
    }
    /**
     * Gets the translations folder for a module
     * @param ModuleBase $module
     * @return string Returns the folder of the module translationss
     */
    static function ModuleTranslationsFolder(ModuleBase $module)
    {
        $class = new \ReflectionClass($module);
        $classFile = Str::Replace('\\', '/', $class->getFileName());
        return Str::Replace('/Modules/', '/Translations/', Path::RemoveExtension($classFile));
    }
    
    /**
     * Gets the translations file for a module
     * @param ModuleBase $module The module
     * @param string $lang The desired language
     * @return string Returns the file with specific translations for a module
     */
    static function ModuleTranslationsFile(ModuleBase $module, $lang)
    {
        $folder = self::ModuleTranslationsFolder($module);
        $file = Path::Combine($folder, $lang);
        return Path::AddExtension($file, 'php');
    }
    
 
    /**
     * Gets the folder for 
     * @param TemplateModule $module
     * @return string
     */
    static function ModuleCustomTemplatesFolder(TemplateModule $module)
    {
        $parentFolder = Path::Combine(PHINE_PATH, 'App/Phine/ModuleTemplates');
        $bundleFolder = Path::Combine($parentFolder, $module->MyBundle());
        return Path::Combine($bundleFolder, $module->MyName());
    }
    
    /**
     * The template file for a page layout
     * @param Layout $layout The page layout
     * @return string Returns the layout template path
     */
    static function LayoutTemplate(Layout $layout)
    {
        $folder = Path::Combine(PHINE_PATH, 'App/Phine/LayoutTemplates');
        $file = Path::Combine($folder, $layout->GetName());
        return Path::AddExtension($file, 'phtml');
    }
    /**
     * The folder to the code templates
     * @param string $bundleName The name of the bundle
     * @return string Returns the folder for code templates
     */
    static function CodeTemplatesFolder($bundleName)
    {
        $bundleFolder = self::BundleFolder($bundleName);
        return Path::Combine($bundleFolder, 'CodeTemplates');
    }
    /**
     * Picks a code template file
     * @param string $bundleName The name of the bundle
     * @param string $fileName The file name with extension
     * @return string Returns a valid path to the code templates
     */
    static function CodeTemplate($bundleName, $fileName)
    {
        return Path::Combine(self::CodeTemplatesFolder($bundleName), $fileName);
    }
    
    /**
     * Returns the server path to the phine backend directory
     * @return string
     */
    static function BackendPath()
    {
        return Path::Combine(PHINE_PATH, 'Public/phine');
    }

    /**
     * The server base path to the rich text editor for the backend
     * return string
     */
    static function BackendRTEPath()
    {
        return Path::Combine(self::BackendPath(), 'rich-text');
    }
    
    /**
     * The backend url; CAUTION: This only works in backend pages!!!
     * return string
     */
    static function BackendUrl()
    {
        return Path::Directory(Request::Uri());
    }
    
    /**
     * The backend rich text editor folder url; CAUTION: This only works in backend pages!!!
     * return string
     */
    static function BackendRTEUrl()
    {
        return Path::Combine(self::BackendUrl(), 'rich-text');
        
    }
    
    /**
     * The files url; CAUTION: This only works in backend pages!!!
     * return string
     */
    static function FilesUrl()
    {
        return Path::Combine(Path::Directory(self::BackendUrl()), '../files');
    }
    /**
     * Gets the url to the upload folder; CAUTION: This only works in backend pages!!!
     * @return string
     */
    static function UploadUrl()
    {
        return Path::Combine(self::FilesUrl(), 'upload');
    }
    
    /**
     * Gets the server path to the files folder
     * @return string
     */
    static function FilesPath()
    {
        return Path::Combine(PHINE_PATH, 'Public/files');
    }
    
    
    /**
     * Gets the server path to the upload folder
     * @return string
     */
    static function UploadPath()
    {
        return Path::Combine(self::FilesPath(), 'upload');
    }
    
    /**
     * The name of the cache file of the 
     * @param Site $site The site
     * @return string Returns the full file path of the sitemap cache file
     */
    static function SitemapCacheFile(Site $site)
    {
        $cacheFolder = Path::Combine(PHINE_PATH, 'App/Phine/Cache/Sitemap');
        $filename = Path::AddExtension($site->GetID(), 'xml');
        return Path::Combine($cacheFolder, $filename);
    }
    /**
     * The name of the cache file
     * @param FrontendModule $module
     * @return string The name of the cache file, it needn't exist yet
     * @throws \Exception Raises an error in case the cache key is not alphanumeric
     */
    static function ContentCacheFile(FrontendModule $module)
    {
        $file = $module->Content()->GetID();
        $cacheKey = $module->CacheKey();
        if ($cacheKey)
        {
            if (!ctype_alnum($cacheKey))
            {
                throw new \Exception(Trans('Core.CacheKey.Error.NotAlphaNumeric'));
            }
            $file .= '-' . $cacheKey;
        }
        $cacheFolder = Path::Combine(PHINE_PATH, 'App/Phine/Cache/Content');
        return Path::AddExtension(Path::Combine($cacheFolder, $file), 'phtml');
    }
    
    /**
     * Gets the database folder
     * @return string The database folder
     */
    static function DatabaseFolder()
    {
        return Path::Combine(PHINE_PATH, 'App/Phine/Database');
    }
}

