<?php

namespace Phine\Bundles\Core\Logic\Access\Frontend;

use Phine\Framework\Access\Base\Guard;
use Phine\Framework\Access\Base\Action;
use Phine\Framework\Access\Base\GrantResult;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Content;
use Phine\Bundles\Core\Logic\Util\MembergroupUtil;
use App\Phine\Database\Core\Membergroup;
use Phine\Framework\System\Date;
use Phine\Bundles\Core\Logic\Util\PublishDateUtil;

/**
 * Guard for frontend page and content access  
 */
class MemberGuard extends Guard
{
    /**
     *
     * @return Accessor 
     */
    protected function CreateAccessor()
    {
        return new Accessor();
    }

    /**
     * Gets the current frintend user
     * @return User
     */
    public function GetMember()
    {
        $accessor = $this->Accessor();
        if ($accessor->IsUndefined())
        {
            return null;
        }
        return $accessor->Member();
    }
    
    /**
     * The groups the current member is assigned to
     * @var Membergroup[]
     */
    private $groups = null;
    
    /**
     * The groups of the current member
     * @return Membergroup[] Returns the groups of the current member
     */
    private function Groups()
    {
        if ($this->groups === null)
        {
            $this->groups = array();
            if ($this->GetMember())
            {
                $this->groups = MembergroupUtil::MemberMembergroups($this->GetMember());
            }
        }
        return $this->groups;
    }
    
    /**
     * Check access to an object in the frontend
     * @param Action $action The action taken on the object
     * @param mixed $onObject The object; page and contents are currently supported
     * @return GrantResult Returns the check result
     */
    public function Grant(Action $action, $onObject)
    {
        if ($onObject instanceof Page)
        {
            return $this->GrantOnPage($onObject);
        }
        if ($onObject instanceof Content)
        {
            return $this->GrantOnContent($onObject, $action);
        }
        else
        {
            throw new \LogicException('Frontend access check not implemented for type '. get_class($onObject));
        }
        return GrantResult::Allowed();
    }
    
    
    /**
     * Checks access to an item by its properties and assigned groups
     * @param boolean $guestsOnly True if guests only see the item
     * @param boolean $publish True if item is generally published
     * @param Date $from The start date of publishing
     * @param Date $to The end date of publishing
     * @param Membergroup[] $groups Groups assigned to the item
     * @return GrantResult
     */
    private function GrantByProperties($guestsOnly, $publish, Date $from = null, Date $to = null, array $groups = array())
    {
        if (!PublishDateUtil::IsPublishedNow($publish, $from, $to))
        {
            return GrantResult::NoAccess();
        }
        if ($this->GetMember() && $guestsOnly)
        {
            return GrantResult::NoAccess();
        }
        if (count($groups) == 0)
        {
            return GrantResult::Allowed();
        }
        if (!$this->GetMember())
        {
            return GrantResult::LoginRequired();
        }
        $groupIDs = Membergroup::GetKeyList($groups);
        $memberGroupIDs = Membergroup::GetKeyList($this->Groups());
        return count(array_intersect($groupIDs, $memberGroupIDs)) ? GrantResult::Allowed() :
            GrantResult::NoAccess();
    }
    
    /**
     * Checks a page for access rights
     * @param Page $page The page for the access check
     * @return GrantResult Returns the check result
     */
    private function GrantOnPage(Page $page)
    {
        $groups = MembergroupUtil::PageMembergroups($page);
        return $this->GrantByProperties($page->GetGuestsOnly(), $page->GetPublish(), 
                $page->GetPublishFrom(), $page->GetPublishTo(), $groups);
        
    }
    
    /**
     * Checks access to a content
     * @param Content $content The content
     * @return GrantResult The result of the check
     */
    private function GrantOnContent(Content $content)
    {
       $groups = MembergroupUtil::ContentMembergroups($content);
       return $this->GrantByProperties($content->GetGuestsOnly(), $content->GetPublish(), 
               $content->GetPublishFrom(), $content->GetPublishTo(), $groups);
    }
}