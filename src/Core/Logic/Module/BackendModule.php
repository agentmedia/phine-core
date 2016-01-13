<?php
namespace Phine\Bundles\Core\Logic\Module;
use Phine\Framework\System\IO\Path;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Access\Backend\UserGuard;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Modules\Backend\Overview;

/**
 * The base class for backend modules
 */
abstract class BackendModule extends TemplateModule
{   
    /**
     * Gets the template file
     * @return string
     */
    final function TemplateFile()
    {
        return $this->BuiltInTemplateFile();
    }
    
    /**
     * The side navigation index; if -1, not present
     * @return int
     */
    public function SideNavIndex()
    {
        return -1;
    }
    
    /**
     * Renders a block by name
     * @param string $block
     * @return string
     */
    public function RenderBlock($block)
    {
        $templateFile = $this->BuiltInTemplateFile();
        $blockFile = Path::AddExtension($templateFile, $block, true);
        $blockFileExt = Path::AddExtension($blockFile, 'phtml');
        if (!File::Exists($blockFileExt))
        {
            return '';
        }
        ob_start();
        require $blockFileExt;
        return ob_get_clean();
    }
    
    /**
     * The backend access guard
     * @var UserGuard
     */
    private static $guard;
    
    /**
     * 
     * Gets the current user guard
     * @return UserGuard
     */
    final public static function Guard()
    {
        if (!self::$guard)
        {
            self::$guard = new UserGuard();
        }
        return self::$guard;
    }
    
    protected function BeforeInit()
    {
        if (!self::Guard()->Allow(BackendAction::Read(), $this))
        {
            //TODO: message
            Response::Redirect(BackendRouter::ModuleUrl(new Overview()));
            return false;
        }
        return parent::BeforeInit();
    }
    
}

