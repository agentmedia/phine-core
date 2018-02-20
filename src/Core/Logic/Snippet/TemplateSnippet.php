<?php
namespace Phine\Bundles\Core\Logic\Snippet;

use Phine\Framework\System\IO\Path;
use Phine\Framework\System\Str;
/*
 * Represents a snippet rendered by template
 */
abstract class TemplateSnippet
{   
    /**
     * Returns the template file
     * @return string
     */
    protected function TemplateFile()
    {
        $class = new \ReflectionClass($this);
        $classFile = Str::Replace('\\', '/', $class->getFileName());
        $templatePath = Str::Replace('/Snippets/', '/Templates/Snippets/', $classFile);
        return Path::AddExtension($templatePath, 'phtml', true);
    }
    
    /**
     * Gets the template contents by requiring the template file
     * @return string
     */
    function Render()
    {
        ob_start();
        require $this->TemplateFile();
        return ob_get_clean();
    }
 
}

