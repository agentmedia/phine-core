<?php

namespace Phine\Bundles\Core\Logic\Rendering;
use Phine\Database\Core\Area;
use Phine\Database\Core\Page;

use Phine\Database\Access;

use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Bundles\Core\Logic\Rendering\ContentsRenderer;

class PageAreaRenderer
{
    
    /**
     *
     * @var Area
     */
    private $area;
    
    /**
     *
     * @var PageContentTreeProvider
     */
    private $tree;
    
    /**
     *
     * @var Page 
     */
    private $page;
    
    function __construct(Page $page, Area $area)
    {
        $this->page = $page;
        $this->area = $area;
        $this->tree = new PageContentTreeProvider($page, $area);
    }
   
    function Render()
    {
        $renderer = new ContentsRenderer($this->tree->TopMost(), $this->tree);
        return $renderer->Render();
    }
}

