<?php
namespace Phine\Bundles\Core\Snippets\TreeBranches\Base;

use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Tree\IContentTreeProvider;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use Phine\Bundles\Core\Modules\Backend\ModuleForm;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Access\Base\GrantResult;
use App\Phine\Database\Core\Content;
use Phine\Framework\Database\Objects\TableObject;

/**
 * ase class for a content tree branch
 */
abstract class ContentBranch extends TemplateSnippet
{
    /**
     * The current content tree item
     * @var mixed
     */
    protected $item; 
    
    /**
     * The current child
     * @var mixed
     */
    protected $child;
    
    /**
     *
     * @var ITreeProvider
     */
    private $tree;
    
    /**
     * The frontend module
     * @var FrontendModule
     */
    protected $module;
    
    /**
     *
     * @var string
     */
    private $editUrl = null;
    
    /**
     * The content below the (tree) item
     * @var Content
     */
    private $content;
    /**
     * Initializes a new content branch
     * @param mixed $item The content tree item
     */
    function __construct($item)
    {
        $this->item = $item;
        $this->tree = $this->TreeProvider();
        $this->child = $this->tree->FirstChildOf($this->item);
        $this->content = $this->tree->ContentByItem($this->item);
        $this->module = ClassFinder::CreateFrontendModule($this->content->GetType());
        $this->module->SetTreeItem($this->tree, $this->item);
    }
    /**
     * The tree provider for the branch
     * @return IContentTreeProvider
     */
    protected abstract function TreeProvider();
    
    /**
     * Grant creation in root node
     * @return GrantResult
     */
    protected abstract function GrantCreateInRoot();
    
    /**
     * The basic edit/create parameters for the branch
     * @return array 
     */
    protected abstract function EditParams();
    /**
     * Gets the next child
     * @return mixed
     */
    final protected function NextChild()
    {
        $child = $this->child;
        $this->child = $this->tree->NextOf($this->child);
        return $child;
    }
    
    /**
     * 
     * @return int
     */
    final protected function ParentID()
    {
        $parent = $this->tree->ParentOf($this->item);
        return $parent ? $parent->GetID() : '0';
    }
    
    /**
     * Gets the id of the item
     * @return int
     */
    final protected function ItemID()
    {
        return $this->item->GetID();
    }
    /**
     * The url for creating content in the current item
     * @return string
     */
    final protected function CreateInUrl()
    {
        $args = $this->EditParams();
        $args['parent'] = $this->item->GetID();
        return BackendRouter::ModuleUrl(new ModuleForm(), $args);
    }
    
    final protected function EditUrl()
    {
        if ($this->editUrl === null)
        {
            $contentForm = $this->module->ContentForm();
            if (!$contentForm)
            {
                $this->editUrl = '';
            }
            else
            {
                $args = array('content'=>$this->content->GetID());
                $args += $this->EditParams();
                $this->editUrl = BackendRouter::ModuleUrl($contentForm, $args);
            }
        }
        return $this->editUrl;
    }
    
    /**
     * The url for creating content after the current item
     * @return type
     */
    final protected function CreateAfterUrl()
    {
        $args = $this->EditParams();
        $args['previous'] = $this->item->GetID();
        return BackendRouter::ModuleUrl(new ModuleForm(), $args);
    }
    
    /**
     * shortcut to the module backend displayname
     * @return string
     */
    final protected function DisplayName()
    {
        return $this->module->BackendName();
    }
    
    /**
     * Shortcut to the module's "chlidren allowed" marker
     * @return boolean
     */
    final protected function AllowChildren()
    {
        return $this->module->AllowChildren();
    }
    
    /**
     * True if the the content can be edited
     * @return Boolean
     */
    final protected function CanEdit()
    {
        $form = $this->module->ContentForm();
        return BackendModule::Guard()->Allow(BackendAction::Edit(), $this->content) 
                && BackendModule::Guard()->Allow(BackendAction::Read(), $form);
    }
    
     /**
     * True if the the content can be edited
     * @return Boolean
     */
    final protected function CanDelete()
    {
        return BackendModule::Guard()->Allow(BackendAction::Delete(), $this->content);
    }
    
    /**
     * True if the content can be created in
     * @return Boolean
     */
    final protected function CanCreateIn()
    {
        return $this->AllowChildren() && 
            BackendModule::Guard()->Allow(BackendAction::Create(), $this->content);
    }
    
    /**
     * Returns true if content cane be moved
     * @return boolen
     */
    final protected function CanMove()
    {
        return BackendModule::Guard()->Allow(BackendAction::Move(), $this->content);
    }
    
    /**
     * True if there can be content created after
     * @return boolean
     */
    final protected function CanCreateAfter()
    {
        $parentItem = $this->tree->ParentOf($this->item);
        if ($parentItem)
        {
            $parent = $this->tree->ContentByItem($parentItem);
            return BackendModule::Guard()->Allow(BackendAction::Create(), $parent);
        }
        return $this->GrantCreateInRoot()->ToBool();
    }
}

