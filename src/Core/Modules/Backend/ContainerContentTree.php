<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Tree\ContainerContentTreeProvider;
use App\Phine\Database\Core\Container;
use App\Phine\Database\Core\ContainerContent;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Modules\Backend\ContainerList;
use Phine\Framework\Access\Base\GrantResult;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

class ContainerContentTree extends BackendModule
{
 
    
    /**
     * The container of the content tree
     * @var Container
     */
    protected $container;

    /**
     * The tree provider
     * @var ContainerContentTreeProvider
     */
    private $tree;
    
    /**
     * True if container has contents
     * @var bool
     */
    protected $hasContents = false;
    
    /**
     * The current container content
     * @var ContainerContent
     */
    protected $containerContent;
    
    /**
     * Optional pre-selected container content
     * @var ContainerContent
     */
    protected $selected;
    

    protected function Init()
    {
        $this->container = new Container(Request::GetData('container'));
        $selectedID = Request::GetData('selected');
        $this->selected = $selectedID ? ContainerContent::Schema()->ByID($selectedID) :  null;
        
        if (!$this->container->Exists())
        {
            //TODO: error
            Response::Redirect(BackendRouter::ModuleUrl(new ContainerList()));
            return true;
        }
        $this->tree = new ContainerContentTreeProvider($this->container);
        $this->containerContent = $this->tree->TopMost();
        $this->hasContents = (bool)$this->containerContent;
        return parent::Init();
    }

    protected function CreateFormUrl()
    {
        $args = array('container'=>$this->container->GetID());
        return BackendRouter::ModuleUrl(new ModuleForm(), $args);
    }

    protected function NextContainerContent()
    {
        $containerContent = $this->containerContent;
        $this->containerContent = $this->tree->NextOf($this->containerContent);
        return $containerContent;
    }
    
    /**
     * True if contents can be added to root element
     * @return boolean
     */
    protected function CanCreateIn()
    {
        return self::Guard()->GrantAddContentToContainer($this->container)->ToBool()
                && self::Guard()->Allow(BackendAction::UseIt(), new ModuleForm());
         
    }
    /**
     * The url for the ajax json response
     * @return string
     */
    protected function JsonUrl()
    {
        return BackendRouter::AjaxUrl(new JsonContainerContentTree());
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the container list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new ContainerList());
    }
}
