<?php

namespace Phine\Bundles\Core\Logic\Tree;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Access;
use App\Phine\Database\Core\Site;

/**
 * The tree provider for the web pages
 */
class PageTreeProvider extends TableObjectTreeProvider
{
    /**
     * The web site of the page tree
     * @var Site
     */
    private $site;
    
    /**
     * Creates the provider for a site
     * @param Site $site
     */
    function __construct(Site $site)
    {
        $this->site = $site;
    }
    /**
     * Gets the first and root page of the site
     * @return Page
     */
    public function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tbl = Page::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Site'), $sql->Value($this->site->GetID()))
            ->And_($sql->IsNull($tbl->Field('Parent')))
            ->And_($sql->IsNull($tbl->Field('Previous')));
        
        return Page::Schema()->First($where);
    }
    
    /**
     * Gets the next page
     * @param Page $item
     * @return Page
     */
    public function NextOf($item)
    {
        return Page::Schema()->ByPrevious($item);
    }
    
    /**
     * Gets the parent page
     * @param Page $item
     * @return Page Returns the parent, if exists
     */
    public function ParentOf($item)
    {
        return $item->GetParent();
    }

    /**
     * Gets the previous page
     * @param Page $item
     * @return Page Returns the previous page
     */
    public function PreviousOf($item)
    {
        return $item->GetPrevious();
    }
    
    /**
     * Saves the page
     * @param Page $item
     */
    public function Save($item)
    {
        $item->Save();
    }
    
    /**
     * Sets the parent
     * @param Page $item The page
     * @param Page $parent The parent
     */
    public function SetParent($item, $parent)
    {
        $item->SetParent($parent);
    }
    
    /**
     * Returns the first child of the page
     * @param Page $item
     * @return Page Returns the first child
     */
    public function FirstChildOf($item)
    {
        $sql = Access::SqlBuilder();
        $tbl = Page::Schema()->Table();
        if ($item)
        {
            $where = $sql->Equals($tbl->Field('Parent'), $sql->Value($item->GetID()))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        
            return Page::Schema()->First($where);
        }
        else
        {
            return $this->TopMost();
        }
    }
    
    /**
     * Sets previous page
     * @param Page $item The page
     * @param Page $previous The previous page
     */
    public function SetPrevious($item, $previous)
    {
        $item->SetPrevious($previous);
    }
}

