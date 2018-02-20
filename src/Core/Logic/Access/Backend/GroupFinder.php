<?php
namespace Phine\Bundles\Core\Logic\Access\Backend;
use App\Phine\Database\Core\Usergroup;
use App\Phine\Database\Core\Content;
use Phine\Bundles\Core\Logic\Tree\ContentTreeUtil;
use App\Phine\Database\Core\Page;


/**
 * Provides methods to find assigned groups taking into account inheritance
 */
class GroupFinder
{
    /**
     * Finds the user group by searching in element tree
     * @param Content $content The content
     * @return Usergroup The assigned user group
     */
    static function FindContentGroup(Content $content)
    {
        $result = null;
        $currContent = $content;
        do
        {
            $result = $currContent->GetUserGroup();
            $currContent = ContentTreeUtil::ParentOf($currContent);
        } 
        while (!$result && $currContent);
        if ($result)
        {
            return $result;
        }
        return self::GetUpperContentGroup($content);
    }
    
    /**
     * Gets the upper countent user group
     * @param Content $content The content
     * @return Usergroup Gets the content rights of the upper, containing element (layout, page, container)
     */
    private static function GetUpperContentGroup(Content $content)
    {
        if ($content->GetPageContent())
        {
            return self::FindPageGroup($content->GetPageContent()->GetPage());
        }
        else if ($content->GetLayoutContent())
        {
            return $content->GetLayoutContent()->
                        GetArea()->GetLayout()->GetUserGroup();
        }
        else if ($content->GetContainerContent())
        {
            return $content->GetContainerContent()->
                        GetContainer()->GetUserGroup();
        }
        return null;
    }
    
    /**
     * Gets the page's user group
     * @param Page $page
     * @return Usergroup
     */
    static function FindPageGroup(Page $page)
    {
        $currPage = $page;
        $result = null;
        do 
        {
            $result = $currPage->GetUserGroup();
            $currPage = $currPage->GetParent();
        } 
        while (!$result && $currPage);
        if (!$result && $page->GetSite())
        {
            return $page->GetSite()->GetUserGroup();
        }
        return $result;
    }
}

