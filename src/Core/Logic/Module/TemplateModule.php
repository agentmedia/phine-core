<?php
namespace Phine\Bundles\Core\Logic\Module;

use Phine\Framework\System\IO\Path;
use Phine\Framework\System\String;
/*
 * Represents a module rendered by template
 */
abstract class TemplateModule extends ModuleBase
{
    
    /**
     * The gathered output string
     * @var string
     */
    protected $output = '';


    
    /**
     * In overriden files, gets the path to the template file
     * @return string Returns the path to the template file
     */
    abstract function TemplateFile();
    
    /**
     * Returns the template file that comes with the bundle module
     * @return string
     */
    protected final function BuiltInTemplateFile()
    {
        $class = new \ReflectionClass($this);
        $classFile = String::Replace('\\', '/', $class->getFileName());
        $templatePath = String::Replace('/Modules/', '/Templates/', $classFile);
        return Path::AddExtension($templatePath, 'phtml', true);
    }
    
    /**
     * 
     * Gathers the output string and stores it in the member 'output'
     */
    protected final function GatherOutput()
    {
        ob_start();
        require $this->TemplateFile();
        $this->output = ob_get_clean();
    }
}

