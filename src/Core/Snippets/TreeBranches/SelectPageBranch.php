<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches;

use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use App\Phine\Database\Core\Page;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;

class SelectPageBranch extends TemplateSnippet
{
    /**
     * The currently page
     * @var Page
     */
    protected $page; 
    
    /**
     * The current child
     * @var Page
     */
    protected $child;
    
    /**
     *
     * @var PageTreeProvider
     */
    private $tree;
    
    /**
     * True if the page is selected
     * @var boolean
     */
    protected $checked = false;
    
    /**
     * Selected page
     * @var Page
     */
    protected $selected;
    
    /**
     * True if the current page is disabled
     * @var boolean
     */
    protected $disabled;
    function __construct(Page $page, Page $selected = null, $disabled = false)
    {
        $this->page = $page;
        $this->selected = $selected;
        $this->checked = $this->page->Equals($selected);
        $this->tree = new PageTreeProvider($this->page->GetSite());
        $this->child = $this->tree->FirstChildOf($this->page);
        $this->disabled = $disabled;
    }
    
    protected function NextChild()
    {
        $child = $this->child;
        $this->child = $this->tree->NextOf($this->child);
        return $child;
    }
    
    protected function ParentID()
    {
        $parent = $this->tree->ParentOf($this->page);
        return $parent ? $parent->GetID() : '0';
    }
}

