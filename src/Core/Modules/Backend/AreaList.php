<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Tree\AreaListProvider;
use App\Phine\Database\Core\Area;
use App\Phine\Database\Core\Layout;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;

/**
 * The area list
 */
class AreaList extends BackendModule
{

    /**
     *
     * @var Layout
     */
    protected $layout;


    /**
     *
     * @var AreaListProvider
     */
    private $listProvider;
    
    /**
     *
     * @var Area
     */
    protected $area;
    
    /**
     * True if any area exists
     * @var boolean
     */
    protected $hasAreas = false;

    /**
     * Initializes the list
     * @return boolean
     */
    protected function Init()
    {
        $this->layout = new Layout(Request::GetData('layout'));
        if (!$this->layout->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new LayoutList()));
            return true;
        }
        $this->listProvider = new AreaListProvider($this->layout);
        
        $this->area = $this->listProvider->TopMost();
        $this->hasAreas = (bool) $this->area;
        return parent::Init();
    }

    /**
     * Create form url for the first area
     * @return string
     */
    protected function CreateFormUrl()
    {
        $args = array('layout'=> $this->layout->GetID());
        return BackendRouter::ModuleUrl(new AreaForm(), $args);
    }
    /**
     * The create url for an area after another
     * @param Area $area
     * @return string
     */
    protected function CreateAfterUrl(Area $area)
    {
        $args = array('layout'=>$this->layout->GetID());
        $args['previous'] = $area->GetID();
        return BackendRouter::ModuleUrl(new AreaForm(), $args);
    }
    
    /**
     * The edit form url
     * @param Area $area
     * @return string
     */
    protected function EditUrl(Area $area)
    {
        $args = array('layout'=>$this->layout->GetID());
        $args['area'] = $area->GetID();
        return BackendRouter::ModuleUrl(new AreaForm(), $args);
    }
    
    /**
     * Back link url to the layout list
     * @return string
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new LayoutList());
    }

    /**
     * Gets the next area
     * @return Area
     */
    protected function NextArea()
    {
        $area = $this->area;
        $this->area = $this->listProvider->NextOf($this->area);
        return $area;
    }
    
    /**
     * The json url for the ajax "tree"
     * @return string
     */
    protected function JsonUrl()
    {
        return BackendRouter::AjaxUrl(new JsonAreaList());
    }
    
    /**
     * 
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Area()) &&
                self::Guard()->Allow(BackendAction::UseIt(), new AreaForm());
    }
    
    /**
     * True if area can be edited
     * @param Area $area
     * @return bool Returns true if the area can be edited
     */
    protected function CanEdit(Area $area)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $area) &&
                self::Guard()->Allow(BackendAction::UseIt(), new AreaForm());
    }
    
    protected function CanDelete(Area $area)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $area);
    }
}