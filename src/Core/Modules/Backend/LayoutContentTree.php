<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Database\Core\Area;
use Phine\Database\Core\LayoutContent;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Modules\Backend\LayoutList;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Access\Base\GrantResult;

class LayoutContentTree extends BackendModule
{
 
    
    /**
     * The area of the content tree
     * @var Area
     */
    protected $area;

    /**
     * The tree provider
     * @var LayoutContentTreeProvider
     */
    private $tree;
    
    /**
     * True if area has contents
     * @var bool
     */
    protected $hasContents = false;
    
    /**
     * The current Layout content
     * @var LayoutContent
     */
    protected $layoutContent;

    /**
     * Optional pre-selected layout content
     * @var LayoutContent
     */
    protected $selected;

    protected function Init()
    {
        $this->area = new Area(Request::GetData('area'));
        $selectedID = Request::GetData('selected');
        $this->selected = $selectedID ? LayoutContent::Schema()->ByID($selectedID) :  null;
        if (!$this->area->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new LayoutList()));
            return true;
        }
        $this->tree = new LayoutContentTreeProvider($this->area);
        $this->layoutContent = $this->tree->TopMost();
        $this->hasContents = (bool)$this->layoutContent;
        return parent::Init();
    }

    protected function CreateFormUrl()
    {
        $args = array('area'=>$this->area->GetID());
        return BackendRouter::ModuleUrl(new ModuleForm(), $args);
    }

    protected function NextLayoutContent()
    {
        $layoutContent = $this->layoutContent;
        $this->layoutContent = $this->tree->NextOf($this->layoutContent);
        return $layoutContent;
    }
    
    /**
     * True if contents can be added to root element
     * @return boolean
     */
    protected function CanCreateIn()
    {
       return self::Guard()->GrantAddContentToLayoutArea($this->area)->ToBool()
               && self::Guard()->Allow(BackendAction::UseIt(), new ModuleForm());
    }
    /**
     * The url for the ajax json response
     * @return string
     */
    protected function JsonUrl()
    {
        return BackendRouter::AjaxUrl(new JsonLayoutContentTree());
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the layout list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new LayoutList());
    }
}
