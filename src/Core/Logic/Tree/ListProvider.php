<?php
namespace Phine\Bundles\Core\Logic\Tree;
trait ListProvider
{
    
    public function FirstChildOf($item)
    {
        if (!$item)
        {
            return $this->TopMost();
        }
        return null;
    }

    public final function ParentOf($item)
    {
        return null;
    }


    public final function SetParent($item, $parent)
    {
    }
    
     /**
     * Gets the sorted item list
     * @return mixed[] Returns the items in their given order
     */
    function ToArray()
    {
        $result = array();
        $item = $this->TopMost();
        while ($item)
        {
            $result[] = $item;
            $item = $this->NextOf($item);
        }
        return $result;
    }
    
    /**
     * Gets the last item
     * @return mixed Retuens the last list item
     */
    function Last() {
        $items = $this->ToArray();
        $cnt = count($items);
        return $cnt > 0 ? $items[$cnt -1] : null;
    }
}


