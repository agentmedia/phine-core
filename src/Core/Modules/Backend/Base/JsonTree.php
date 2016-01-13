<?php

namespace Phine\Bundles\Core\Modules\Backend\Base;

use Phine\Bundles\Core\Logic\Module\JsonModule;
use Phine\Framework\System\Http\Request;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Framework\Database\Objects\TableSchema;
use Phine\Bundles\Core\Logic\Tree\ITreeProvider;
use Phine\Bundles\Core\Logic\Tree\TreeBuilder;

/**
 * Json handler for tree actions on table objects
 */
abstract class JsonTree extends JsonModule
{

    /**
     *
     * @var TreeBuilder
     */
    private $tree;

    /**
     * The table object that serves as a tree item
     * @var TableObject
     */
    protected $item;

    protected function Init()
    {
        if (!$this->InitItem())
        {
            $this->AttachError('Core.JsonTree.Error.ItemNotFound');
            return true;
        }
        $this->tree = new TreeBuilder($this->TreeProvider());
        try
        {
            $this->performAction();
        }
        catch (\Exception $ex)
        {
            $this->AttachException($ex);
        }
        return parent::Init();
    }

    private function performAction()
    {
        switch (Request::PostData('action'))
        {
            case 'insertIn':
                $this->InsertIn();
                break;

            case 'insertAfter':
                $this->InsertAfter();
                break;

            case 'delete':
                $this->BeforeDelete();
                $this->Delete();
                break;

            default:
                $this->AttachError(Trans('Core.JsonTree.Error.UndefinedAction'));
        }
    }
    
    /**
     * Allows inherited classes to perform clean ups before item is deleted
     */
    protected function BeforeDelete()
    {}

    /**
     * Returns the schema for the table object that serves as a tree item 
     * @return TableSchema
     */
    protected abstract function TableSchema();

    /**
     * Returns the specific tree provider in derived classes
     * @return ITreeProvider
     */
    protected abstract function TreeProvider();

    private function InitItem()
    {
        $this->item = $this->TableSchema()->ByKeyValue(Request::PostData('item'));
        return $this->item != null;
    }

    private function InsertIn()
    {
        $parent = $this->TableSchema()->ByKeyValue(Request::PostData('parent'));
        $this->tree->Insert($this->item, $parent);
    }

    private function InsertAfter()
    {
        $previous = $this->TableSchema()->ByKeyValue(Request::PostData('previous'));
        $this->tree->Insert($this->item, null, $previous);
    }

    protected function Delete()
    {
        $this->tree->Remove($this->item);
    }

}
