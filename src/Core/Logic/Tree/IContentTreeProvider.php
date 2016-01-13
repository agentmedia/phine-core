<?php
namespace Phine\Bundles\Core\Logic\Tree;
use Phine\Database\Core\Content;

interface IContentTreeProvider extends ITreeProvider
{
    /**
     * Gets the tree item for the content
     * @param Content $content The content
     * @return mixed Returns the tree item associated to the content
     */
    function ItemByContent(Content $content);
    
    /**
     * Gets the content of the tree item
     * @return Content Returns the content of the tree item
     * @param mixed $item The tree item
     */
    function ContentByItem($item);
    
    
    /**
     * Attaches the content to the item
     * @param mixed $item
     * @param Content $content
     */
    function AttachContent($item, Content $content);
    
}

