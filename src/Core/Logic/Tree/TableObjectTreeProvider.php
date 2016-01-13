<?php

namespace Phine\Bundles\Core\Logic\Tree;
use Phine\Framework\Database\Objects\TableObject;

abstract class TableObjectTreeProvider implements ITreeProvider
{
    public function Equals($item1, $item2)
    {
        return $this->_Equals($item1, $item2);
    }
    
    private function _Equals(TableObject $item1 = null, TableObject $item2= null)
    {
        if ($item1 === null && $item2 !== null)
        {
            return false;
        }
        else if ($item1 !== null && $item2 === null)
        {
            return false;
        }
        else if ($item1 === null && $item2 === null)
        {
            return true;
        }
        return $item1->Equals($item2);
    }
    
    /**
     * Saves the table object
     * @param TableObject $item
     */
    public function Save($item)
    {
        $this->_Save($item);
    }
    
    
    /**
     * Saves the item; type save
     * @param TableObject $item
     */
    private function _Save(TableObject $item)
    {
        $item->Save();
    }
    
    /**
     * Saves the item
     * @param TableObject $item
     */
    public function Delete($item)
    {
        $this->_Delete($item);
    }
    
    /**
     * Deletes the item; type save
     * @param TableObject $item
     */
    private function _Delete(TableObject $item)
    {
        $item->Delete();
    }
}