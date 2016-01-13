<?php

namespace Phine\Bundles\Core\Logic\Tree;
use Phine\Database\Core\PageContent;
use Phine\Database\Core\Content;
use Phine\Database\Access;
use Phine\Database\Core\Area;
use Phine\Database\Core\Page;

class PageContentTreeProvider extends TableObjectTreeProvider implements IContentTreeProvider
{
    /**
     * The page of the content tree
     * @var Page
     */
    private $page;
    
    /**
     * The area of the content tree
     * @var Area
     */
    private $area;
    
    /**
     * Creates a new page tree provider
     * @param Page $page The page of tree
     * @param Area $area The area on the page where the content tree resides
     */
    function __construct(Page $page, Area $area)
    {
        $this->page = $page;
        $this->area = $area;
    }
    /**
     * Returns the root and first content element in a page and area
     * @return PageContent Returns the first and root content
     */
    function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tbl = PageContent::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Page'), $sql->Value($this->page->GetID()))->
                And_($sql->Equals($tbl->Field('Area'), $sql->Value($this->area->GetID())))->
                And_($sql->IsNull($tbl->Field('Parent')))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        
        return PageContent::Schema()->First($where);
    }

    /**
     * Gets the next sibling of the item
     * @param PageContent $item
     * @return PageContent
     */
    public function NextOf($item)
    {
        return PageContent::Schema()->ByPrevious($item);
    }
    
    /**
     * Gets the parent of the item
     * @param PageContent $item
     * @return PageContent Returns the parent
     */
    public function ParentOf($item)
    {
        return $item->GetParent();
    }

    /**
     * Gets the previous element of the item
     * @param PageContent $item
     * @return PageContent
     */
    public function PreviousOf($item)
    {
        return $item->GetPrevious();
    }
    
    /**
     * Sets the parent (but doesn't save it)
     * @param PageContent $item
     * @param PageContent $parent
     */
    public function SetParent($item, $parent)
    {
        $item->SetParent($parent);
    }
    
    /**
     * Gets the first child of the item or the top most if the given item is null
     * @param PageContent $item
     * @return PageContent
     */
    public function FirstChildOf($item)
    {
        if ($item)
        {
            $sql = Access::SqlBuilder();
            $tbl = PageContent::Schema()->Table();
            $where = $sql->Equals($tbl->Field('Parent'), $sql->Value($item->GetID()))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        
            return PageContent::Schema()->First($where);
        }
        else
        {
            return $this->TopMost();
        }
    }
    
    /**
     * Sets the previous element of the item (but doesn't save it)
     * @param PageContent $item The item
     * @param PageContent $previous The previous item
     */
    public function SetPrevious($item, $previous = null)
    {
        $item->SetPrevious($previous);
    }
    
    
    /**
     * Links page content (providing tree info) and content in database
     * @param PageContent $item
     * @param Content $content
     */
    public function AttachContent($item, Content $content)
    {
        $item->SetContent($content);
        $item->Save();
        $content->SetPageContent($item);
        $content->Save();
    }
    
    /**
     * Gets content by item
     * @param PageContent $item
     * @return Content
     */
    public function ContentByItem($item)
    {
        return $item ? $item->GetContent() : null;
    }
    
    /**
     * Gets the associated page content by content
     * @param Content $content
     * @return PageContent Returns the page content
     */
    public function ItemByContent(Content $content)
    {
        return $content->GetPageContent();
    }

}

