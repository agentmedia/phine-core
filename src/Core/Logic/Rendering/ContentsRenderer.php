<?php

namespace Phine\Bundles\Core\Logic\Rendering;;
use Phine\Bundles\Core\Logic\Tree\IContentTreeProvider;
class ContentsRenderer
{
    /**
     * The root item
     * @var type 
     */
    private $first;
    
    
    /**
     * The tree
     * @var IContentTreeProvider
     */
    private $tree;
    function __construct($first, IContentTreeProvider $tree)
    {
        $this->first = $first;
        $this->tree = $tree;
    }
    
    function Render()
    {
        
        $result = '';
        $item = $this->first;
        
        while ($item)
        {
            $renderer = new ContentRenderer($item, $this->tree);
            $result .= $renderer->Render();
            $item = $this->tree->NextOf($item);
        }
        
        return $result;
    }
}


