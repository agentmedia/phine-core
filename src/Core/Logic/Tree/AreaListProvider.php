<?php

namespace Phine\Bundles\Core\Logic\Tree;
use App\Phine\Database\Core\Area;
use App\Phine\Database\Core\Layout;
use App\Phine\Database\Access;

class AreaListProvider extends TableObjectTreeProvider
{
    
    use ListProvider;
    /**
     * The layout of the area list
     * @var Layout
     */
    private $layout;
    
    /**
     * Creates the area list provider
     * @param Layout $layout
     */
    function __construct(Layout $layout)
    {
        $this->layout = $layout;
    }
    
    /**
     * Deletes the area
     * @param Area $item
     */
    public function Delete($item)
    {
        $item->Delete();
    }

    /**
     * Gets the next area of a given one
     * @param Area $item The previous area
     */
    public function NextOf($item)
    {
        return Area::Schema()->ByPrevious($item);
    }

    /**
     * Gets the previous area
     * @param Area $item
     */
    public function PreviousOf($item)
    {
       return $item->GetPrevious();
    }

    /**
     * 
     * @param Area $item
     * @param Area $previous
     */
    public function SetPrevious($item, $previous)
    {
        $item->SetPrevious($previous);
    }

    /**
     * Returns the top most area
     * @return Area
     */
    public function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tblArea = Area::Schema()->Table();
        $where = $sql->Equals($tblArea->Field('Layout'), $sql->Value($this->layout->GetID()))
                ->And_($sql->IsNull($tblArea->Field('Previous')));
        
        return Area::Schema()->First($where);
    }
}

