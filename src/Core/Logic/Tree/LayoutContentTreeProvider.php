<?php

namespace Phine\Bundles\Core\Logic\Tree;
use Phine\Database\Core\LayoutContent;
use Phine\Database\Core\Content;
use Phine\Database\Access;
use Phine\Database\Core\Area;

class LayoutContentTreeProvider extends TableObjectTreeProvider implements IContentTreeProvider
{
    
    /**
     * The area for the layout content
     * @var Area
     */
    private $area;
    
    /**
     * Creates a layout content tree provider
     * @param Area $area The area of the layout content tree
     */
    function __construct(Area $area)
    {
        $this->area = $area;
    }
    /**
     * Gets the top most content of the layout area
     * @param Area $area The area
     * @return LayoutContent The first and root layout content
     */
    function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tbl = LayoutContent::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Area'), $sql->Value($this->area->GetID()))->
                And_($sql->IsNull($tbl->Field('Parent')))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        return LayoutContent::Schema()->First($where);
    }

    /**
     * Gets the next sibling of the item
     * @param LayoutContent $item
     * @return LayoutContent
     */
    public function NextOf($item)
    {
        return LayoutContent::Schema()->ByPrevious($item);
    }
    
    /**
     * Gets the parent item
     * @param LayoutContent $item
     * @return LayoutContent
     */
    public function ParentOf($item)
    {
        return $item->GetParent();
    }

    /**
     * Gets the previous sibling
     * @param LayoutContent $item
     * @return LayoutContent 
     */
    public function PreviousOf($item)
    {
        return $item->GetPrevious();
    }

    /**
     * Sets the parent of the item
     * @param LayoutContent $item The item
     * @param LayoutContent $parent The parent
     */
    public function SetParent($item, $parent)
    {
        $item->SetParent($parent);
    }
    
    /**
     * Gets the first child of the item
     * @param LayoutContent $item The item
     * @return LayoutContent Returns the first child
     */
    public function FirstChildOf($item)
    {
        if ($item)
        {
            $sql = Access::SqlBuilder();
            $tbl = LayoutContent::Schema()->Table();
            $where = $sql->Equals($tbl->Field('Parent'), $sql->Value($item->GetID()))
                    ->And_($sql->IsNull($tbl->Field('Previous')));

            return LayoutContent::Schema()->First($where);
        }
        else
        {
            return $this->TopMost();
        }
    }

    /**
     * Sets the previous sibling (without saving the item)
     * @param LayoutContent $item The item
     * @param LayoutContent $previous The new previous sibling
     */
    public function SetPrevious($item, $previous = null)
    {
        $item->SetPrevious($previous);
    }

    /**
     * Attaches item and content
     * @param LayoutContent $item
     * @param Content $content
     */
    public function AttachContent($item, Content $content)
    {
        $item->SetContent($content);
        $item->Save();
        $content->SetLayoutContent($item);
        $content->Save();
    }

    public function ContentByItem($item)
    {
        return $item ? $item->GetContent() : null;
    }

    public function ItemByContent(Content $content)
    {
        return $content->GetLayoutContent();
    }
}