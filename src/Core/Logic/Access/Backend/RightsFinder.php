<?php
namespace Phine\Bundles\Core\Logic\Access\Backend;
use Phine\Bundles\Core\Logic\Tree\ContentTreeUtil;
use Phine\Database\Core\BackendPageRights;
use Phine\Database\Core\Content;
use Phine\Database\Core\Page;
/**
 * Provides methods to find rights taking into account inheritance
 */
class RightsFinder
{
     /**
     * Finds the content rigths by searching in element tree
     * @param Content $content The content
     * @return BackendContentRights The rights of the contents
     */
    static function FindContentRights(Content $content)
    {
        $result = null;
        $currContent = $content;
        do
        {
            $result = $currContent->GetUserGroupRights();
            $currContent = ContentTreeUtil::ParentOf($currContent);
        } 
        while (!$result && $currContent);
        if ($result)
        {
            return $result;
        }
        return self::GetUpperContentRights($content);
    }
    
    
    
    /**
     * Gets the content rights of the upper, containing element (page, layout, container) 
     * @param Content $content The content
     * @return BackendContentRights Returns the assigned content rights
     */
    private function GetUpperContentRights(Content $content)
    {
        if ($content->GetPageContent())
        {
            $area = $content->GetPageContent()->GetArea();
            if ($area->GetLocked())
            {
                return null;
            }
            $pageRights = self::FindPageRights($content->GetPageContent()->GetPage());
            if ($pageRights)
            {
                return $pageRights->GetContentRights();
            }
        }
        else if ($content->GetLayoutContent())
        {
            $area = $content->GetLayoutContent()->GetArea();
            if ($area->GetLocked())
            {
                return null;
            }
            $layoutRights = $content->GetLayoutContent()->GetArea()->GetLayout()->GetUserGroupRights();
            if ($layoutRights)
            {
                return $layoutRights->GetContentRights();
            }
        }
        else if ($content->GetContainerContent())
        {
            $containerRights = $content->GetContainerContent()->GetContainer()->GetUserGroupRights();
            if ($containerRights)
            {
                return $containerRights->GetContentRights();
            }
        }
        return null;
    }
    
    /**
     * Gets the rights of the page
     * @param Page $page
     * @return BackendPageRights
     */
    static function FindPageRights(Page $page)
    {
        $currPage = $page;
        $result = null;
        do 
        {
            $result = $currPage->GetUserGroupRights();
            $currPage = $currPage->GetParent();
        } 
        while (!$result && $currPage);
        if (!$result && $page->GetSite())
        {
            $siteRights = $page->GetSite()->GetUserGroupRights();
            if ($siteRights)
            {
                return $siteRights->GetPageRights();
            }
        }
        return $result;
    }
}

