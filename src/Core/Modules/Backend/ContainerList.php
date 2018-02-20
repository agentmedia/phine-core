<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Module\Traits;
use App\Phine\Database\Core\Container;
use App\Phine\Database\Access;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Bundles\Core\Logic\Hooks\IDeleteHook;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The list of containers in the backend
 */
class ContainerList extends BackendModule
{
    use Traits\TableObjectRemover;
    
    /**
     * A list of containers
     * @var Container[]
     */
    protected $containers;
    
    /**
     * Delete hookds
     * @var IDeleteHook[]
     */
    private static $deleteHooks = array();
    function Init()
    {
        $sql = Access::SqlBuilder();
        $tblContainer = Container::Schema()->Table();
        
        $orderBy = $sql->OrderList($sql->OrderAsc($tblContainer->Field('Name')));
        $this->containers = Container::Schema()->Fetch(false, null, $orderBy);
        
        return parent::Init();
    }
    
    static function AddDeleteHook(IDeleteHook $deleteHook)
    {
        self::$deleteHooks[] = $deleteHook;
    }
    /**
     * The url to the page with the container edit/create form
     * @param Container $container If container is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(Container $container = null)
    {
        
        $args = array();
        if ($container)
        {
            $args['container'] = $container->GetID();
        }
        return BackendRouter::ModuleUrl(new ContainerForm(), $args);
    }
    
    /**
     * The side navigation index
     * @return int Returns the index in the side nav
     */
    public function SideNavIndex()
    {
        return 3;
    }
    
    /**
     * Gets the container that is requested to be removed
     * @return Container The removal container
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? new Container($id) : null;
    }
    
    protected function BeforeRemove(TableObject $deleteObject)
    {
        foreach (self::$deleteHooks as $deleteHook)
        {
            $deleteHook->BeforeDelete($deleteObject);
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportContainerAction($deleteObject, Action::Delete());
    }
    

    /**
     * The url to the content tree of the container
     * @param Container $container
     * @return string
     */
    protected function ContentTreeUrl(Container $container)
    {
        return BackendRouter::ModuleUrl(new ContainerContentTree(), array('container'=>$container->GetID()));
    }
    
    
    /**
     * True if a new container can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Container())
            && self::Guard()->Allow(BackendAction::UseIt(), new ContainerForm());
    }
    /**
     * True if the container can be edited
     * @param Container $container
     * @return boolean
     */
    protected function CanEdit(Container $container)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $container)
            && self::Guard()->Allow(BackendAction::UseIt(), new ContainerForm());
    }
    
    /**
     * True if the container can be removed
     * @param Container $container
     * @return boolean
     */
    protected function CanDelete(Container $container)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $container);
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the overview
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new Overview());
    }
}