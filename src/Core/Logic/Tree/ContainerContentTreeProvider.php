<?php

namespace Phine\Bundles\Core\Logic\Tree;
use Phine\Database\Core\ContainerContent;
use Phine\Database\Core\Content;
use Phine\Database\Access;
use Phine\Database\Core\Container;

class ContainerContentTreeProvider extends TableObjectTreeProvider implements IContentTreeProvider
{
    /**
     * The container for the content
     * @var Container
     */
    private $container;
    
    /**
     * Creates a container content tree provider
     * @param Container $container The container of the content tree
     */
    function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * Gets the top most content of the container
     * @return ContainerContent The first and root container content
     */
    function TopMost()
    {
        $sql = Access::SqlBuilder();
        $tbl = ContainerContent::Schema()->Table();
        $where = $sql->Equals($tbl->Field('Container'), $sql->Value($this->container->GetID()))->
                And_($sql->IsNull($tbl->Field('Parent')))
                ->And_($sql->IsNull($tbl->Field('Previous')));
        return ContainerContent::Schema()->First($where);
    }

    /**
     * Gets the next sibling of the item
     * @param ContainerContent $item
     * @return ContainerContent
     */
    public function NextOf($item)
    {
        return ContainerContent::Schema()->ByPrevious($item);
    }
    
    /**
     * Gets the parent item
     * @param ContainerContent $item
     * @return ContainerContent
     */
    public function ParentOf($item)
    {
        return $item->GetParent();
    }

    /**
     * Gets the previous sibling
     * @param ContainerContent $item
     * @return ContainerContent 
     */
    public function PreviousOf($item)
    {
        return $item->GetPrevious();
    }

    /**
     * Sets the parent of the item
     * @param ContainerContent $item The item
     * @param ContainerContent $parent The parent
     */
    public function SetParent($item, $parent)
    {
        $item->SetParent($parent);
    }
    
    /**
     * Gets the first child of the item
     * @param ContainerContent $item The item
     * @return ContainerContent Returns the first child
     */
    public function FirstChildOf($item)
    {
        if ($item)
        {
            $sql = Access::SqlBuilder();
            $tbl = ContainerContent::Schema()->Table();
            $where = $sql->Equals($tbl->Field('Parent'), $sql->Value($item->GetID()))
                    ->And_($sql->IsNull($tbl->Field('Previous')));

            return ContainerContent::Schema()->First($where);
        }
        else
        {
            return $this->TopMost();
        }
    }

    /**
     * Sets the previous sibling (without saving the item)
     * @param ContainerContent $item The item
     * @param ContainerContent $previous The new previous sibling
     */
    public function SetPrevious($item, $previous = null)
    {
        $item->SetPrevious($previous);
    }

    /**
     * Attaches item and content
     * @param ContainerContent $item
     * @param Content $content
     */
    public function AttachContent($item, Content $content)
    {
        $item->SetContent($content);
        $item->Save();
        $content->SetContainerContent($item);
        $content->Save();
    }

    /**
     * Gets content by item
     * @param ContainerContent $item
     * @return Content
     */
    public function ContentByItem($item)
    {
        return $item ? $item->GetContent() : null;
    }

    /**
     * 
     * @param Content $content
     * @return type
     */
    public function ItemByContent(Content $content)
    {
        return $content->GetContainerContent();
    }
}