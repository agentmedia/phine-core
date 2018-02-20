<?php
namespace Phine\Bundles\Core\Logic\Tree;
use App\Phine\Database\Core\Content;
/**
 * Helper class for easy access to a content tree
 */
class ContentTreeUtil
{
    /**
     * 
     * Chooses the right provider for the content
     * @return IContentTreeProvider
     */
    static function GetTreeProvider(Content $content)
    {
        if ($content->GetPageContent())
        {
            return new PageContentTreeProvider($content->GetPageContent()->GetPage(), 
                    $content->GetPageContent()->GetArea());
        }
        if ($content->GetLayoutContent())
        {
            return new LayoutContentTreeProvider($content->GetLayoutContent()->GetArea());
        }
        if ($content->GetContainerContent())
        {
            return new ContainerContentTreeProvider($content->GetContainerContent()->GetContainer());
        }
        return null;
    }
    
    /**
     * Returns the parent content of the item
     * @param Content $content Returns the parent
     * @return Content the parent content in the tree
     */
    static function ParentOf (Content $content)
    {
        $provider = self::GetTreeProvider($content);
        if (!$provider)
        {
            return null;
        }
        $item = $provider->ItemByContent($content);
        return $provider->ContentByItem($provider->ParentOf($item));
    }
   
    
    /**
     * Returns the first child of the content
     * @param Content $content The parent content
     * @return Content Returns the first child in the tree
     */
    static function FirstChildOf (Content $content)
    {
        $provider = self::GetTreeProvider($content);
        if (!$provider)
        {
            return null;
        }
        $item = $provider->ItemByContent($content);
        return $provider->ContentByItem($provider->FirstChildOf($item));
    }
    
    /**
     * Gets tbe next sibling in the content trees
     * @param Content $content
     * @return Content Returns the next sibling of the content
     */
    static function NextOf(Content $content)
    {
        $provider = self::GetTreeProvider($content);
        if (!$provider)
        {
            return null;
        }
        $item = $provider->ItemByContent($content);
        return $provider->ContentByItem($provider->NextOf($item));
    }
    /**
     * Gets the previous content sibling
     * @param Content $content
     * @return Content Returns the previous sibling in the content tree
     */
    static function PreviousOf(Content $content)
    {
        $provider = self::GetTreeProvider($content);
        if (!$provider)
        {
            return null;
        }
        $item = $provider->ItemByContent($content);
        return $provider->ContentByItem($provider->PreviousOf($item));
    }   
}