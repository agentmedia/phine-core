<?php
namespace Phine\Bundles\Core\Logic\Installation\InstallUtil;
/**
 * Represents a page in the install tool
 */
class Page
{
    
    private static $steps;
    /**
     * The page content
     * @var Content
     */
    protected $content;
    function __construct(Content $content)
    {
        $this->content = $content;
    }
    
    /**
     * 
     * @return sel
     */
    static function Steps()
    {
        if (self::$steps === null)
        {
            self::$steps = array();
            self::$steps['index'] = 'Password';
            self::$steps['database'] = 'Database Setup';
            self::$steps['administrator'] = 'Admin Settings';
            self::$steps['finish'] = 'Finish';
        }
        return self::$steps;
    }
    
    /**
     * Gets the next step name
     * @param string $step The current step key name
     * @return string Returns the next step name or null if 
     */
    static function NextStep($step)
    {
        $keys = array_keys(self::Steps());
        $index = array_search($step, $keys);
        if ($index !== false && $index < count($keys) - 1)
        {
           return $keys[$index + 1];
        }
        return null;
    }
    
    protected function StepText($step = '')
    {
        $steps = self::Steps();
        if ($step == '')
        {
            return $steps[$this->content->Step()];
        }
        return $steps[$step];
    }
    /**
     * Renders the install util page
     * @return string Gets the full page html
     */
    function Render()
    {
        ob_start();
        require __DIR__ . '/Templates/Page.phtml';
        return ob_get_clean();
    }
}