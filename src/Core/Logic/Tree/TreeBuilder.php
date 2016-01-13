<?php

namespace Phine\Bundles\Core\Logic\Tree;

class TreeBuilder
{
    /**
     * The tree provider
     * @var ITreeProvider
     */
    private $provider;
    
    /**
     * Creates a 
     * @param ITreeProvider $provider
     */
    function __construct(ITreeProvider $provider)
    {
        $this->provider = $provider;
    }
    
    /**
     * Inserts the item
     * @param mixed $item
     * @param mixed $parent
     * @param mixed $previous
     */
    function Insert($item, $parent = null, $previous = null)
    {
        if ($previous)
        {
            $parent = $this->provider->ParentOf($previous);
        }
        $oldNext = $this->provider->NextOf($item);
        $oldPrev = $this->provider->PreviousOf($item);
        $oldFirst = $this->provider->FirstChildOf($parent);
        if (!($oldPrev && $previous && $this->provider->Equals($oldPrev, $previous)))
        {
            $this->CloseCutGap($oldPrev, $oldNext, $item);
        }
        $this->UpdateInsertItem($item, $parent, $previous);
        
        if (!$previous)
        {
            $this->AssureFirstChild($item, $oldFirst);
        }
    }
    
    
    private function CloseCutGap($oldPrev, $oldNext, $item)
    {
        if ($oldNext)
        {
            //temporarily unset previous of item 
            $this->provider->SetPrevious($item, null);
            $this->provider->Save($item);
            $this->provider->SetPrevious($oldNext, $oldPrev);
            $this->provider->Save($oldNext);
        }   
    }
    
    private function AssureFirstChild($item, $oldFirst)
    {
        if ($oldFirst && $oldFirst != $item)
        {
            //establish item as new first child
            $this->provider->SetPrevious($oldFirst, $item);
            $this->provider->Save($oldFirst);
        }
    }
    private function UpdateInsertItem($item, $parent, $previous)
    {
        if ($previous)
        {
            $oldPrevNext = $this->provider->NextOf($previous);
            if ($oldPrevNext)
            {
                $this->provider->SetPrevious($oldPrevNext, $item);
                $this->provider->Save($oldPrevNext);
            }
        }
        $this->provider->SetParent($item, $parent);
        $this->provider->SetPrevious($item, $previous);
        $this->provider->Save($item);
    }
   
    /**
    * Removes the item
    * @param mixed $item
    */
    function Remove($item)
    {
        $prev = $this->provider->PreviousOf($item);
        $next = $this->provider->NextOf($item);
        if ($next)
        {
            $this->provider->SetPrevious($next, $prev);
        }
        $this->provider->Delete($item);
        if ($next) 
        {
            $this->provider->Save($next);
        }
    }
}