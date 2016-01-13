<?php
namespace Phine\Bundles\Core\Logic\Routing;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Module\ModuleBase;
use Phine\Bundles\Core\Logic\Module\JsonModule;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Database\Core\Content;
use Phine\Bundles\Core\Logic\Access\Backend\UserGuard;

/**
 * Router for backend modules
 */
class BackendRouter
{
    /**
     * Gets the url for a module
     * @param BackendModule $module The module
     * @param array $args
     * @return string
     */
    static function ModuleUrl(BackendModule $module, array $args = array())
    {
        $allArgs = array('module'=> ClassFinder::ModuleType($module)) + $args;
        return 'index.php?' . http_build_query($allArgs);
    }
    
    /**
     * Returns the url to ab ajax module whose content is rendered as is
     * @param ModuleBase $module The module
     * @param array $args The url parameters
     * @return string Returns the ajax url
     */
    static function AjaxUrl(ModuleBase $module, array $args = array())
    {
        $allArgs = array('module'=> ClassFinder::ModuleType($module)) + $args;
        $guard = new UserGuard();
        $user = $guard->GetUser();
        if (!$user)
        {
            throw new \Exception('Security breach on ajax url: no backend user logged in');
        }
        $allArgs['__backendUser'] = $user->GetID();
        return 'ajax.php?' . http_build_query($allArgs);
    }
    
    /**
     * Gets the module from request url
     * @return ModuleBase Returns the backend module
     */
    static function UrlModule()
    {
        $module = Request::GetData('module');
        return ClassFinder::CreateBackendModule($module);
    }
    
    /**
     * Gets the url of the content tree for a content element
     * @param Content $content
     * @return string
     */
    static function ContentTreeUrl(Content $content)
    {
        $args = array();
        $pageContent = $content->GetPageContent();
        if ($pageContent)
        {
            $args['page'] = $pageContent->GetPage()->GetID();
            $args['area'] = $pageContent->GetArea()->GetID();
            return self::ModuleUrl(new \Phine\Bundles\Core\Modules\Backend\PageContentTree, $args);
        }
        $layoutContent = $content->GetLayoutContent();
        if ($layoutContent)
        {
            $args['area'] = $layoutContent->GetArea()->GetID();
            return self::ModuleUrl(new \Phine\Bundles\Core\Modules\Backend\LayoutContentTree, $args);
        }
        $containerContent = $content->GetContainerContent();
        if($containerContent)
        {
            $args['container'] = $containerContent->GetContainer()->GetID();
            return self::ModuleUrl(new \Phine\Bundles\Core\Modules\Backend\ContainerContentTree, $args);
        }
    }
}

