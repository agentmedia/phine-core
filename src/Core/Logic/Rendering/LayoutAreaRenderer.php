<?php

namespace Phine\Bundles\Core\Logic\Rendering;
use App\Phine\Database\Core\Area;

use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Bundles\Core\Logic\Rendering\ContentsRenderer;

class LayoutAreaRenderer
{
    
    /**
     *
     * @var Area
     */
    private $area;
    
    /**
     *
     * @var LayoutContentTreeProvider
     */
    private $tree;
    function __construct(Area $area)
    {
        $this->tree = new LayoutContentTreeProvider($area);
        $this->area = $area;
    }
    
    function Render()
    {
        $renderer = new ContentsRenderer($this->tree->TopMost(), $this->tree);
        return $renderer->Render();
    }
    
    

}

