<?php

namespace Phine\Bundles\Core\Logic\Module;
use Phine\Database\Core;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\Path;
use Phine\Bundles\Core\Logic\Tree\IContentTreeProvider;
use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Bundles\Core\Logic\Tree\ContainerContentTreeProvider;
use Phine\Bundles\Core\Logic\Rendering\ContentsRenderer;
use Phine\Framework\System\Date;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Access\Frontend\MemberGuard;
use Phine\Bundles\Core\Logic\Caching\FileCacher;
use Phine\Bundles\Core\Logic\Rendering\PageRenderer;
/**
 * Base class for frontend modules
 */
abstract class FrontendModule extends TemplateModule
{
    
    /**
     * The tree item for rendering
     * @var mixed 
     */
    protected $item;
    /**
     * The content for rendering
     * @var Core\Content 
     */
    protected $content;
    
    /**
     * The tree provider
     * @var IContentTreeProvider
     */
    protected $tree;
    
    /**
     * Helper for caching the content
     * @var FileCacher
     */
    private $fileCacher;
    /**
     * The template file
     * @return string
     */
    public function TemplateFile()
    {
        if (!$this->AllowCustomTemplates() ||
                !$this->content || !$this->content->GetTemplate())
        {
            return $this->BuiltInTemplateFile();
        }
        $file = Path::Combine(PathUtil::ModuleCustomTemplatesFolder($this), $this->content->GetTemplate());
        return Path::AddExtension($file, 'phtml');
    }
    
    /**
     * Can be overriden to allow custom templates
     * @return boolean
     */
    public function AllowCustomTemplates()
    {
        return false;
    }
    
    /**
     * Gets the item that shall be rendered
     * @return mixed $item
     */
    public function GetItem()
    {
        return $this->item;
    }
    
    /**
     * Sets tree and tree item
     * @param IContentTreeProvider $tree
     * @param mixed $item The tree item containing the render content
     */
    public function SetTreeItem(IContentTreeProvider $tree, $item)
    {
        $this->tree = $tree;
        $this->item = $item;
        $this->content = $this->tree->ContentByItem($item);
    }
    
    /**
     * Gets a display name for backend issues, can be overridden
     * @return string The display name
     */
    public function BackendName()
    {
        if ($this->ContentForm())
        {
            $this->ContentForm()->ReadTranslations();    
        }
        return Trans($this->MyBundle() . '.' . $this->MyName() . '.BackendName');
    }
    
    /**
     * Returns true if children of thiis element are allowed
     * @return boolean;
     */
    public abstract function AllowChildren();    
    
    /**
     * Gets the related BackendForm, if module is customizable
     * @return ContentForm Returns the related form or null if no customization allowed
     */
    public abstract function ContentForm();
    
    /**
     * Provides public access to the content's css class
     * @return string Gets the css class
     */
    public function CssClass()
    {
        return $this->content->GetCssClass();
    }
    
    
    /**
     * Provides public access to the content's css id
     * @return string Gets the css id
     */
    public function CssID()
    {
        return $this->content->GetCssID();
    }
    
    protected function RenderChildren()
    {
        if ($this->content->GetLayoutContent())
        {
            return $this->RenderLayoutChildren();
        }
        else if ($this->content->GetPageContent())
        {
            return $this->RenderPageChildren();
        }
        else if ($this->content->GetContainerContent())
        {
            return $this->RenderContainerChildren();
        }
    }
    
    private function RenderLayoutChildren()
    {
        $layoutContent = $this->content->GetLayoutContent();
        $provider = new LayoutContentTreeProvider($layoutContent->GetArea());
        
        $renderer = new ContentsRenderer($provider->FirstChildOf($layoutContent), $provider);
        return $renderer->Render();
    }
    
    private function RenderContainerChildren()
    {
        $containerContent = $this->content->GetContainerContent();
        $provider = new ContainerContentTreeProvider($containerContent->GetContainer());
        
        $renderer = new ContentsRenderer($provider->FirstChildOf($containerContent), $provider);
        return $renderer->Render();
    }
    
    
    private function RenderPageChildren()
    {
        $pageContent = $this->content->GetPageContent();
        $provider = new PageContentTreeProvider($pageContent->GetPage(), $pageContent->GetArea());
        
        $renderer = new ContentsRenderer($provider->FirstChildOf($pageContent), $provider);
        return $renderer->Render();
    }
    
    /**
     * The currently rendered content
     * @return Core\Content Returns the content when rendered
     */
    public function Content()
    {
        return $this->content;
    }
    /**
     * Optional cache key for separate contents f.e. depending on request parameters
     * @return string
     */
    public function CacheKey()
    {
        return '';
    }
    
    /**
     * Gets cache content if necessary
     * @return boolean
     */
    protected function BeforeInit()
    {
        //todo: check access rights
        $cacheFile = PathUtil::ContentCacheFile($this);
        $this->fileCacher = new FileCacher($cacheFile, $this->content->GetCacheLifetime());
        if ($this->fileCacher->MustUseCache())
        {
            $this->output = $this->fileCacher->GetFromCache();
            return true;
        }
        return parent::BeforeInit();
    }
    /**
     * Stores to cache if necessary
     */
    protected function AfterGather()
    {
        if ($this->fileCacher->MustStoreToCache())
        {
            $this->fileCacher->StoreToCache($this->output);
        }
        parent::AfterGather();
    }
    /**
     * True if cache file contents must be used
     * @param strinh $cacheFile The cache file
     * @return boolean
     */
    private function MustUseCache($cacheFile)
    {
        $seconds = $this->content->GetCacheLifetime();
        if ($seconds == 0)
        {
            return false;
        }
        if (!File::Exists($cacheFile))
        {
            return false;
        }
        $now = Date::Now();
        $lastMod = File::GetLastModified($cacheFile);
        if ($now->TimeStamp() - $lastMod->TimeStamp() < $seconds)
        {
            return true;
        }
        return false;
    }
    
    private function MustStoreToCache()
    {
       return $this->content->GetCacheLifetime() > 0;
    }
    
    
    /**
     * The frontend access guard
     * @var MemberGuard
     */
    private static $guard;
    
    /**
     * 
     * Gets the current member guard
     * @return MemberGuard
     */
    final public static function Guard()
    {
        if (!self::$guard)
        {
            self::$guard = new MemberGuard();
        }
        return self::$guard;
    }
    
    /**
     * Gets the currently rendered page (convenience function)
     * @return Core\Page;
     */
    final protected function CurrentPage()
    {
        return PageRenderer::Page();
    }
}
