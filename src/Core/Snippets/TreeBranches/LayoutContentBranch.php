<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches;

use Phine\Database\Core\LayoutContent;
use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Database\Core\Area;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Access\Base\GrantResult;
/**
 * The layout content branch
 */
class LayoutContentBranch extends Base\ContentBranch
{
    
    /**
     *
     * @var Area
     */
    private $area;
    
    /**
     * Initializes a new layout content branch
     * @param LayoutContent $layoutContent The page content 
     */
    function __construct(LayoutContent $layoutContent)
    {
        $this->area = $layoutContent->GetArea();
        parent::__construct($layoutContent);
    }
    
    /**
     * The edit/create url base parameters
     * @return array Returns the parameters for creating/editing
     */
    protected function EditParams()
    {
        $params = array();
        $params['area'] = $this->area->GetID();
        return $params;
    }
    
    /**
     * Returns the tree provider
     * @return PageContentTreeProvider
     */
    protected function TreeProvider()
    {
        return new LayoutContentTreeProvider($this->area);
    }

    /**
     * @return GrantResult
     */
    protected function GrantCreateInRoot()
    {
        return BackendModule::Guard()->GrantAddContentToLayoutArea($this->area);
    }

}

