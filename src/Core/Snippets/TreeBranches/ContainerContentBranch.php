<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches;

use App\Phine\Database\Core\ContainerContent;
use Phine\Bundles\Core\Logic\Tree\ContainerContentTreeProvider;
use App\Phine\Database\Core\Container;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Access\Base\GrantResult;

/**
 * The container content branch
 */
class ContainerContentBranch extends Base\ContentBranch
{
    
    /**
     *
     * @var Container
     */
    private $container;
    
    /**
     * Initializes a new container content branch
     * @param ContainerContent $containerContent The page content 
     */
    function __construct(ContainerContent $containerContent)
    {
        $this->container = $containerContent->GetContainer();
        parent::__construct($containerContent);
    }
    
    /**
     * The edit/create url base parameters
     * @return array Returns the parameters for creating/editing
     */
    protected function EditParams()
    {
        $params = array();
        $params['container'] = $this->container->GetID();
        return $params;
    }
    
    /**
     * Returns the tree provider
     * @return ContainerContentTreeProvider
     */
    protected function TreeProvider()
    {
        return new ContainerContentTreeProvider($this->container);
    }

    /**
     * Grant creation in root level 
     * @return GrantResult
     */
    protected function GrantCreateInRoot()
    {
        return BackendModule::Guard()->GrantAddContentToContainer($this->container);
    }

}
