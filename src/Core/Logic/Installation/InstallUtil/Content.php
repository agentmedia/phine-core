<?php
namespace Phine\Bundles\Core\Logic\Installation\InstallUtil;
use Phine\Framework\System\IO\Path;

use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Framework\System\IO\File;

/**
 * Abstract base for a content of the page util
 */
abstract class Content
{
    /**
     * The errors with fields as keys and error texts as values
     * @var array
     */
    private $errors;
    /**
     * The path to the class defintion file
     * @var string
     */
    private $classFile = '';
    
    /**
     * The session key for warnins
     * @var string
     */
    private static $warningsKey = 'installer_warning';
    /**
     * Creates the new content
     */
    public function __construct()
    {
        $this->errors = array();
        if ($this->Step() != 'index' && 
                !$this->IsAllowed())
        {
            Response::Redirect('index.php');
        }
        if (Request::IsPost())
        {
            $this->HandlePost();
        }
    }
    /**
     * The class definition file
     * @return string Returns the path of the file where the content is defined
     */
    private function ClassFile()
    {
        if (!$this->classFile)
        {
            
            $class = new \ReflectionClass($this);
            $this->classFile = $class->getFileName();
        }
        return $this->classFile;
    }
    
    /**
     * Handle post in derived classes
     */
    protected function HandlePost()
    {
        
    }
    /**
     * The "step" name, which is the page file name without extension
     * @return string Returns the step name
     */
    function Step()
    {
        return Path::FilenameNoExtension($this->ClassFile());
    }
    
    /**
     * Renders and returns the html output
     * @return string Returns the html of the content
     */
    function Render()
    {
        $templateDir  = Path::Combine(__DIR__, 'Templates');
        $templateFile = Path::AddExtension(Path::Filename($this->ClassFile()), 'phtml', true);
        ob_start();
        require Path::Combine($templateDir, $templateFile);
        return ob_get_clean();
    }
    
    function RenderEndScript()
    {
        $templateDir  = Path::Combine(__DIR__, 'Templates');
        $templateFile = Path::FilenameNoExtension($this->ClassFile()) . '.endscript.phtml';
        $template = Path::Combine($templateDir, $templateFile);
        if (File::Exists($template))
        {
            ob_start();
            require $template;
            return ob_get_clean();
        }
        return '';
    }
    
    /**
     * Sets an error text to a field
     * @param string $field The field where an error was detected
     * @param string $text The description text for the error
     */
    function SetError($field, $text)
    {
        $this->errors[$field] = $text;
    }
    
    /**
     * True if the field has an error
     * @param string $field The field name
     * @return boolean Returns true if the field has an error
     */
    function HasError($field)
    {
        return array_key_exists($field, $this->errors);
    }
    
    /**
     * Gets the error text for a field
     * @param string $field The field where the error occured
     * @return string Returns the error text
     */
    function Error($field)
    {
        return $this->errors[$field];
    }
    /**
     * Gets the value of a field
     * @param string $field The field name
     * @param bool $trim True if the value shall be trimmed by whitespace characters
     */
    function Value($field, $trim = true)
    {
        $result = Request::IsPost() ? Request::PostData($field) : $this->DefaultValue($field);
        if ($trim)
        {
            return trim($result);
        }
        return $result;
    }
    /**
     * Allows a derived content form to set a default value for a field
     * @param string $field The field
     * @return string Returns the default value that is inserted in case nothing is posted
     */
    protected function DefaultValue($field)
    {
        return '';
    }
    
    /**
     * Redirects to the next step
     */
    function GotoNext()
    {
        $next = Page::NextStep($this->Step());
        Response::Redirect(Path::AddExtension($next, 'php'));
    }
    
    /**
     * True if a correct password was entered in first step
     * @return bool
     */
    function IsAllowed()
    {
        return isset($_SESSION['allowed']) && (bool)$_SESSION['allowed'];
    }
    
    /**
     * Marks the installation as allowed or unallowed
     * @param bool $allow True if installation is allowed, false otherwise
     */
    protected function SetAllowed($allow = true)
    {
        if (!$allow && isset($_SESSION['allowed']))
        {
            unset($_SESSION['allowed']);
        }
        else
        {
            $_SESSION['allowed'] = true;
        }
    }
    /**
     * True if any error was saved
     * @return bool Returns true if the error list is not empty
     */
    function HasErrors()
    {
        return count($this->errors) > 0;
    }
    
    function AddWarning($message)
    {
        if (!isset($_SESSION[self::$warningsKey]))
        {
            $_SESSION[self::$warningsKey] = array();
        }
        
        $_SESSION[self::$warningsKey][] = $message;
    }
    
    
    function GetWarnings()
    {
        $result = array();
        if (isset($_SESSION[self::$warningsKey]))
        {
            $result = $_SESSION[self::$warningsKey];
            unset ($_SESSION[self::$warningsKey]);
        }
        return $result;
    }
    
    function HasWarnings()
    {
        return isset($_SESSION[self::$warningsKey]) &&
            is_array($_SESSION[self::$warningsKey]) &&
            count($_SESSION[self::$warningsKey]) > 0;
    }
    
}

