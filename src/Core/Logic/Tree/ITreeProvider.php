<?php
namespace Phine\Bundles\Core\Logic\Tree;

/**
 * Provides an interface for tree items
 */
interface ITreeProvider
{
    
    /**
     * 
     * Gets the top most item
     * @return mixed Returns the first item in the tree
     */
    function TopMost();
    /**
     * Gets the next item
     * @param mixed $item The current item
     * @return mixed Returns the next item after the given item
     */
    function NextOf($item);
    
    /**
     * Gets the previous item
     * @param mixed $item The current item
     * @return mixed Returns the previous item before the given item
     */
    function PreviousOf($item);
    
    /**
     * Gets the parent item
     * @param mixed $item The current item
     * @return mixed Returns the parent item of the given item
     */
    function ParentOf($item);
    
    /**
     * Gets the first child item of the given item
     * @param mixed $item The current item
     * @return mixed Returns the first child item of the given item
     */
    function FirstChildOf($item);
    /**
     * Deletes the item
     * @param mixed $item
     */
    function Delete($item);
    
    /**
     * Saves the item
     * @param mixed $item
     */
    function Save($item);
    
    /**
     * Sets the parent for the item
     * @param mixed $item The item
     * @param mixed $parent The item's parent
     */
    function SetParent($item, $parent);
    
    /**
     * Sets the previous item
     * @param mixed $item The item
     * @param mixed $previous The previous item
     */
    function SetPrevious($item, $previous);
    
    /**
     * Compares two tree items
     * @param mixed $item1
     * @param mixed $item2
     * @return bool Returns true if the items are equal, false otherwise
     */
    function Equals($item1, $item2);
}

