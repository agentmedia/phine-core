<?php

namespace Phine\Bundles\Core\Logic\Rendering;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Layout;
use App\Phine\Database\Core\Area;
use App\Phine\Database\Access;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\InsertVariables\Replacer;
use Phine\Bundles\Core\Logic\DBEnums\PageType;

/**
 * Renders a Phine CMS page
 */
class PageRenderer
{
    /**
     * The page that shall be rendered
     * @var Page
     */
    private $page;
    
    /**
     * The page layout
     * @var Layout
     */
    private $layout;
    
    /**
     * The page layout areas
     * @var Area[] 
     */
    private $areas = array();
    
    /**
     * The page currently rendered
     * @var Page
     */
    private static $currentPage;
    
    
    function SetPage(Page $page)
    {
        $this->page = $page;
        $this->layout = $page->GetLayout();
        $this->InitAreas();
    }
    /**
     * The page currently rendered
     * @return Page
     */
    public static function Page()
    {
        return self::$currentPage;
    }
    
    /**
     * Initializes the areas and stores them in a list with area names as keys
     */
    private function InitAreas()
    {
        $sql = Access::SqlBuilder();
        $tbl = Area::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Layout'), $sql->Value($this->layout->GetID()))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        $area = Area::Schema()->First($where);
        while ($area)
        {
            $this->areas[$area->GetName()] = $area;
            $area = Area::Schema()->ByPrevious($area);
        }
    }
    
    /**
     * The areas of the page
     * @return Area[] The areas as 
     */
    protected function Areas()
    {
        return $this->areas;
    }
    
    /**
     * Renders the area with the given name
     * @param string $name The area name
     * @return string Returns the rendering output for the area
     * @throws \InvalidArgumentException Raises an exception on invalid area name
     */
    
    protected function RenderArea($name)
    {
        $area = $this->areas[$name];
        if (!$area)
        {
            throw new \InvalidArgumentException("Invalid area name $name");
        }
        $layoutAreaRenderer = new LayoutAreaRenderer($area);
        $result = $layoutAreaRenderer->Render();
       
        $pageAreaRenderer = new PageAreaRenderer($this->page, $area);
        $result .= $pageAreaRenderer->Render();
        return $result;
    }
    
    /**
     * Renders the complete page by requiring the page layout
     * @return string Returns the page contents
     */
    function Render()
    {
        self::$currentPage = $this->page;
        self::$Title = self::$currentPage->GetTitle();
        self::$Description = self::$currentPage->GetDescription();
        self::$Keywords = self::$currentPage->GetKeywords();
        if ($this->page->GetType() == (string)PageType::NotFound())
        {
            header('HTTP/1.0 404 Not Found');
        }
        else if ($this->page->GetType() !== (string)PageType::Normal())
        {
            throw new \Exception('Internal phine error: not normal page called');
        }
        ob_start();
        require PathUtil::LayoutTemplate($this->layout);
        $result = ob_get_clean();
        $replacer = new Replacer();
        return $replacer->RealizeVariables($result);
    }
    
    static function AppendToTitle($appendix)
    {
        self::$Title .= $appendix;
    }
    
    static function AppendToDescriptoin($appendix)
    {
        self::$Description .= $appendix;
    }
    static function AppendToKeywords($appendix)
    {
        self::$Keywords .= $appendix;
    }
    static function PrependToTitle($prependix)
    {
        self::$Title = $prependix . self::$Title;
    }
    
    static function PrependToDescription($prependix)
    {
        self::$Description = $prependix . self::$Description;
    }
    
    
    static function PrependToKeyword($prependix)
    {
        self::$Keywords = $prependix . self::$Keywords;
    }
    /**
     * The page title; it is set ot the database page title when rendering
     * @var string
     */
    public static $Title = '';
    
    
    /**
     * The page description; it is set ot the database page description when rendering
     * @var string
     */
    public static $Description = '';
    
    
    /**
     * The page keywords; it is set ot the database page keywords when rendering
     * @var string
     */
    public static $Keywords = '';
    
}