<?php
namespace Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Framework\System\Http\Response;
use Phine\Framework\System\Http\Request;

/**
 * Trait for template modules that shall delete table objects
 */
trait TableObjectRemover
{
    /**
     * 
     * Gets the object for deletion
     * @return TableObject The object that shall be removed; can be null so nothing is removed
     */
    protected abstract function RemovalObject();
    /**
     * 
     * @return bool
     */
    protected function BeforeInit()
    {
        $deleteObject = $this->RemovalObject();
        if ($deleteObject && $deleteObject->Exists())
        {
            $this->BeforeRemove($deleteObject);
            $deleteObject->Delete();
            return $this->AfterRemove();
        }
        return parent::BeforeInit();
    }
    
    /**
     * Can be used to add logic after deletion in using classes;
     * by default, it redirects to the current url
     */
    protected function AfterRemove()
    {
        Response::Redirect(Request::Uri());
        return true;
    }
    /**
     * Can be used to clear dependencies before table object is really removed
     * @param TableObject $deleteObject The table object that is about to be deleted
     */
    protected function BeforeRemove(TableObject $deleteObject)
    {}
}
