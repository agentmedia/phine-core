<?php

namespace Phine\Bundles\Core\Logic\Rendering;
use Phine\Bundles\Core\Logic\Tree\IContentTreeProvider;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Database\Core\Content;
use Phine\Bundles\Core\Logic\Access\Frontend\MemberGuard;
use Phine\Framework\Access\Base\Action;
use Phine\Bundles\Core\Logic\Translation\ContentTranslator;

/**
 * Renders a content of any type
 */
class ContentRenderer
{
    /**
     * The tree item with the content
     * @var mixed
     */
    private $item;
    /**
     *
     * @var IContentTreeProvider
     */
    private $tree;
    
    /**
     * The rendered content
     * @var Content 
     */
    private $content;
    /**
     * The member guard for access control
     * @var MemberGuard
     */
    private static $guard;
    
    /**
     * Creates a new content renderer
     * @param mixed $item The tree item
     * @param IContentTreeProvider $tree
     */
    function __construct($item, IContentTreeProvider $tree)
    {
        $this->item = $item;
        $this->tree = $tree;
        $this->content = $tree->ContentByItem($item);
    }
    
    /**
     * Gets a singleton member guard object
     * @return MemberGuard Returns a singleton instance of the member guard
     */
    private static function Guard()
    {
        if (!self::$guard)
        {
            self::$guard = new MemberGuard();
        }
        return self::$guard;
    }
    
    /**
     * Renders the content
     * @return string Returns the rendered content
     */
    function Render()
    {
        if (!self::Guard()->Allow(Action::Read(), $this->content))
        {
            return '';
        }
        ContentTranslator::Singleton()->SetContent($this->content);
        $module = ClassFinder::CreateFrontendModule($this->content->GetType());
        $module->SetTreeItem($this->tree, $this->item);
        return $module->Render();
    }
}

