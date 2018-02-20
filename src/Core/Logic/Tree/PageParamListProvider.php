<?php

namespace Phine\Bundles\Core\Logic\Tree;
use App\Phine\Database\Core\PageUrlParameter;
use App\Phine\Database\Core\PageUrl;
use App\Phine\Database\Access;

/**
 * Provider for a list of page url parameters
 */
class PageParamListProvider extends TableObjectTreeProvider
{
    use ListProvider;
    
    /**
     * The page url for the parameter list
     * @var PageUrl
     */
    private $pageUrl;
    
    /**
     * Creates a new instance of the page params list provider
     * @param PageUrl $pageUrl The page url for the parameter list
     */
    public function __construct(PageUrl $pageUrl)
    {
        $this->pageUrl = $pageUrl;
    }
    /**
     * Gets the next parameter
     * @param PageUrlParameter $item
     * @return PageUrlParameter Returns the next parameter or null if last is reached
     */
    public function NextOf($item)
    {
        return PageUrlParameter::Schema()->ByPrevious($item);
    }

    /**
     * Gets the previous parameter
     * @param PageUrlParameter $item
     * @return PageUrlParameter Returns previous parameter or null if first is reached
     */
    public function PreviousOf($item)
    {
        return $item->GetPrevious();
    }

    /**
     * Sets the previous parameter to the current item 
     * @param PageUrlParameter $item The current parameter
     * @param PageUrlParameter $previous The previous of the current for setting
     */
    public function SetPrevious($item, $previous)
    {
        $item->SetPageUrl($previous);
    }
    
    /**
     * Gets the first page url parameter
     * @return PageUrlParameter Returns the first parameter or null if none exists
     */
    public function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tblParams = PageUrlParameter::Schema()->Table();
        $where = $sql->Equals($tblParams->Field('PageUrl'), $sql->Value($this->pageUrl->GetID()))
                ->And_($sql->IsNull($tblParams->Field('Previous')));
        
        return PageUrlParameter::Schema()->First($where);
    }
    
    /**
     * 
     * Converts the parameter list to an associative array
     * @return array Returns the parameter list as an array of name value pairs
     */
    public function ToArray()
    {
        $result = array();
        $item = $this->TopMost();
        while ($item)
        {
            $result[$item->GetName()] = $item->GetValue();
            $item = $this->NextOf($item);
        }
        return $result;
    }

}

