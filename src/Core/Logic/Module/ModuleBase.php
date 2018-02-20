<?php
namespace Phine\Bundles\Core\Logic\Module;

use Phine\Framework\System\Str;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Framework\Localization\PhpTranslator;
/*
 * Represents a module rendered by template
 */
abstract class ModuleBase
{
    
    /**
     * The gathered output string
     * @var string
     */
    protected $output = '';


    /**
     * Reads the translation files
     */
    function ReadTranslations()
    {
        $lang = PhpTranslator::Singleton()->GetLanguage();
        \RequireOnceIfExists(PathUtil::BundleTranslationsFile($this, $lang));
        \RequireOnceIfExists(PathUtil::ModuleTranslationsFile($this, $lang));
    }
    
    
    /**
     * The bundle name
     * @return string
     */
    static function MyBundle()
    {
        $className = \get_class(new static());
        $endPos = strpos($className, '\\Modules\\');
        $bundleNS = Str::Start($className, $endPos);
        $startPos = strrpos($bundleNS, '\\');
        return Str::Part($bundleNS, $startPos + 1);
    }
    
    /**
     *  The module name; which is the class name without namespace
     * @return string Returns the module name
     */
    static function MyName()
    {
        $class = new \ReflectionClass(new static());
        return $class->getShortName();
    }
    
    /**
     * Returns the module type, composed by bundle and module name
     * @return string Returns a simplified type string
     */
    static function MyType()
    {
       return ClassFinder::ModuleType(new static());
    }
    
    /**
     * Gets the desired output
     * @return string Returns the output
     */
    final function Render()
    {
        $this->output = '';
        $this->ReadTranslations();
        if ($this->BeforeInit())
        {
            return $this->output;
        }
        if ($this->Init())
        {
            return $this->output;
        }
        if ($this->BeforeGather())
        {
            return $this->output;
        }
        $this->GatherOutput();
        $this->AfterGather();
        return $this->output;        
    }
    
    /**
     * Initializes the module; Usually this is the place to initialize members
     * @return boolen Return true if rendering should cancel
     */
    protected function Init()
    {
        return false;
    }
    
    /**
     * Happens before init
     * @return boolean return false if rendering shall proceed
     */
    protected function BeforeInit()
    {
        return false;   
    }
    
    
    /**
     * Can be used to achieve special behaviour after init, and before output is gathered
     * @return boolean Return false if rendering shall proceed 
     */
    protected function BeforeGather()
    {
        return false;
    }
    
    /**
     * 
     * Gathers the output string and stores it in the member 'output'
     */
    protected abstract function GatherOutput();
    
    
    /**
     * Executed when output is gathered
     */
    protected function AfterGather()
    {
    }
    
}

