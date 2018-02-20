<?php

namespace Phine\Bundles\Core\Logic\Access\Backend;

use App\Phine\Database\Access;
use Phine\Framework\Access\Base\Guard;
use Phine\Framework\Access\Base\Action;
use Phine\Bundles\Core\Logic\Module\ModuleBase;
use App\Phine\Database\Core\User;
use App\Phine\Database\Core\Usergroup;
use App\Phine\Database\Core\UserUsergroup;
use Phine\Framework\Access\Base\GrantResult;
use App\Phine\Database\Core\Site;
use Phine\Framework\Database\Sql\InList;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Container;
use App\Phine\Database\Core\Layout;
use App\Phine\Database\Core\Content;
use App\Phine\Database\Core\Area;

use App\Phine\Database\Core\BackendPageRights;
use App\Phine\Database\Core\BackendSiteRights;
use App\Phine\Database\Core\BackendContentRights;
use App\Phine\Database\Core\BackendLayoutRights;
use App\Phine\Database\Core\BackendContainerRights;
use App\Phine\Database\Core\ModuleLock;

/**
 * Guard for control access  
 */
class UserGuard extends Guard
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
     * Gets the current backend user
     * @return User
     */
    public function GetUser()
    {
        $accessor = $this->Accessor();
        if ($accessor->IsUndefined())
        {
            return null;
        }
        return $accessor->User();
    }

    /**
     * 
     * @return InList
     */
    private function GetGroupList()
    {
        $groups = $this->GetGroups();
        if (count($groups) == 0)
        {
            return null;
        }

        $sql = Access::SqlBuilder();
        $inList = $sql->InList($sql->Value($groups[0]->GetID()));
        for ($idx = 1; $idx < count($groups); ++$idx)
        {
            $inList->Add($sql->Value($groups[$idx]));
        }
        return $inList;
    }

    /**
     *
     * @var Usergroup[]
     */
    private $groups;

    /**
     * 
     * @return Usergroup[]
     */
    private function GetGroups()
    {
        if ($this->groups === null)
        {
            $this->groups = array();
            if (!$this->GetUser())
            {
                return $this->groups;
            }
            $userGroups = UserUsergroup::Schema()->FetchByUser(true, $this->GetUser());
            foreach ($userGroups as $userGroup)
            {
                $this->groups[] = $userGroup->GetUserGroup();
            }
        }
        return $this->groups;
    }

    private function IsAdministrator()
    {
        if ($this->GetUser())
        {
            return $this->GetUser()->GetIsAdmin();
        }
        return false;
    }

    public function Grant(Action $action, $onObject)
    {
        if (!$this->GetUser())
        {
            return GrantResult::LoginRequired();
        }
        if ($onObject instanceof User)
        {
            return $this->GrantOnUser($action, $onObject);
        }
        if ($this->IsAdministrator())
        {
            return GrantResult::Allowed();
        }
        
        if ($onObject instanceof Site)
        {
            if (!$onObject->Exists() && (string)$action == (string)Action::Create())
            {
                return $this->GrantCreateSite();
            }
            return $this->GrantOnSite($onObject, $action);
        }
        else if ($onObject instanceof Page)
        {
            return $this->GrantOnPage($onObject, $action);
        }
        else if ($onObject instanceof Container)
        {
            if (!$onObject->Exists() && (string)$action == (string)Action::Create())
            {
                return $this->GrantCreateContainer();
            }
            return $this->GrantOnContainer($onObject, $action);
        }
        else if ($onObject instanceof Area)
        {
            return $this->GrantOnArea($onObject, $action);
        }
        else if ($onObject instanceof Layout)
        {
            if (!$onObject->Exists() && (string)$action == (string)Action::Create())
            {
                return $this->GrantCreateLayout();
            }
            return $this->GrantOnLayout($onObject, $action);
        }
        else if ($onObject instanceof Content)
        {
            return $this->GrantOnContent($onObject, $action);
        }
        else if ($onObject instanceOf ModuleBase)
        {
            return $this->GrantOnModule($onObject);
        }
        return GrantResult::NoAccess();
    }
    
    private function GrantOnArea(Area $area, BackendAction $action)
    {
        $layout = $area->GetLayout();
        if ($area->GetLocked() && !$this->GetUser()->Equals($layout->GetUser()))
        {
            return GrantResult::NoAccess();
        }
        return GrantResult::Allowed();
    }
    /**
     * 
     * @param Usergroup $group
     * @param Site $site
     * @param Action $action
     * @return GrantResult
     */
    private function GrantGroupOnSite(Usergroup $group, Site $site, BackendAction $action)
    {
        if (!$group->Equals($site->GetUserGroup()))
        {
            return GrantResult::NoAccess();
        }
        
        $rights = $site->GetUserGroupRights();
        $allowed = false;
        switch ($action)
        {
            case BackendAction::Edit():
                $allowed = $rights->GetEdit();
                break;
            
            case BackendAction::Delete():
                $allowed = $rights->GetRemove();
                break;
            
            case BackendAction::Read():
                $allowed = $this->HasAnySiteRight($rights) ||
                    $this->HasAnyPageRight($rights->GetPageRights()) ||
                    $this->HasAnyContentRight($rights->GetPageRights()->GetContentRights());
                break;
        }
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    
    /**
     * True if any of the page rights is true
     * @param BackendPageRights $pageRights The page right
     * @return boolean Returns true if any access action is allowed
     */
    private function HasAnyPageRight(BackendPageRights $pageRights)
    {
        return $pageRights->GetRemove() ||
                $pageRights->GetCreateIn() ||
                $pageRights->GetEdit() || 
                $pageRights->GetMove();
    }
    
    
    /**
     * True if any of the site rights is true
     * @param BackendSiteRights $siteRights
     * @return boolean Returns true if any access action is allowed
     */
    private function HasAnySiteRight(BackendSiteRights $siteRights)
    {
        return $siteRights->GetEdit() ||
                $siteRights->GetRemove();
    }
    
     /**
     * True if any of the content rights is true
     * @param BackendContentRights $contentRights
     * @return boolean Returns true if any access action is allowed
     */
    private function HasAnyContentRight(BackendContentRights $contentRights)
    {
        return $contentRights->GetEdit() ||
                $contentRights->GetRemove() ||
                $contentRights->GetCreateIn() ||
                $contentRights->GetMove();
    }
    /**
     * True if any of the container rights is true
     * @param BackendContainerRights $containerRights
     * @return boolean Returns true if any access action is allowed
     */
    private function HasAnyContainerRight(BackendContainerRights $containerRights)
    {
        return $containerRights->GetEdit() ||
                $containerRights->GetRemove();
    }
    
    /**
     * True if any of the layout rights is true
     * @param BackendLayoutRights $layoutRights
     * @return boolean Returns true if any access action is allowed
     */
    private function HasAnyLayoutRight(BackendLayoutRights $layoutRights)
    {
        return $layoutRights->GetEdit() ||
                $layoutRights->GetRemove();
    }
    
    private function GrantGroupOnPage(Usergroup $group, Usergroup $pageGroup, BackendPageRights $pageRights, BackendAction $action)
    {
        
        if (!$group->Equals($pageGroup))
        {
            return GrantResult::NoAccess();
        }
        $allowed = false;
        
        switch ($action)
        {
            case BackendAction::Create():
                $allowed = $pageRights->GetCreateIn();
                break;
            
            case BackendAction::Edit():
                $allowed = $pageRights->GetEdit();
                break;
            
            case BackendAction::Move():
                $allowed = $pageRights->GetMove();
                break;
            
            case BackendAction::Delete():
                
                $allowed = $pageRights->GetRemove();
                break;
            
            case BackendAction::Read():
                $allowed = $this->HasAnyPageRight($pageRights) ||
                    $this->HasAnyContentRight($pageRights->GetContentRights());
        }
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    
    private function GrantGroupOnContainer(Usergroup $group, Container $container, BackendAction $action)
    {
        $contGroup = $container->GetUserGroup();
        $contRights = $container->GetUserGroupRights();
        if (!$contGroup->Equals($group))
        {
            return GrantResult::NoAccess();
        }
        $allowed = false;
        switch ($action)
        {
            case BackendAction::Edit():
                $allowed = $contRights->GetEdit();
                break;
            
            case BackendAction::Delete():
                $allowed = $contRights->GetRemove();
                
            case BackendAction::Read():
                $allowed = $this->HasAnyContainerRight($contRights)
                    || $this->HasAnyContentRight($contRights->GetContentRights());
                break;
        }
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    
    
    private function GrantGroupOnLayout(Usergroup $group, Layout $layout, BackendAction $action)
    {
        $layoutGroup = $layout->GetUserGroup();
        $layoutRights = $layout->GetUserGroupRights();
        if (!$layoutGroup->Equals($group))
        {
            return GrantResult::NoAccess();
        }
        $allowed = false;
        switch ($action)
        {
            case BackendAction::Edit():
                $allowed = $layoutRights->GetEdit();
                break;
            
            case BackendAction::Delete():
                $allowed = $layoutRights->GetRemove();
                
            case BackendAction::Read():
                $allowed = $this->HasAnyLayoutRight($layoutRights)
                    || $this->HasAnyContentRight($layoutRights->GetContentRights());
                break;
        }
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    
    /**
     * 
     * @param Layout $layout
     * @param BackendAction $action
     * @return GrantResult
     */
    private function GrantOnLayout(Layout $layout, BackendAction $action)
    {
        if ($this->GetUser()->Equals($layout->GetUser()))
        {
            return GrantResult::Allowed();
        }
        if (!$layout->GetUserGroup() || !$layout->GetUserGroupRights())
        {
            return GrantResult::NoAccess();
        }
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            $result = $this->GrantGroupOnLayout($group, $layout, $action);
            if ($result->Equals(GrantResult::Allowed()))
            {
                return $result;
            }
        }
        return GrantResult::NoAccess();
    }
    
    /**
     * Calculates the grant result for a group on a content
     * @param Usergroup $group The evaluated group
     * @param Usergroup $contentGroup The group of the content
     * @param BackendContentRights $contentRights The rights of the content
     * @param BackendAction $action The action that shall be taken for the content
     * @return GrantResult Returns the calculated result
     */
    private function GrantGroupOnContent(Usergroup $group, Usergroup $contentGroup, BackendContentRights $contentRights, BackendAction $action)
    {
        if (!$group->Equals($contentGroup))
        {
            return GrantResult::NoAccess();
        }
        $allowed = false;
        switch ($action)
        {
            case BackendAction::Create():
                $allowed = $contentRights->GetCreateIn();
                break;
            
            case BackendAction::Edit():
                $allowed = $contentRights->GetEdit();
                break;
            
            case BackendAction::Move():
                $allowed = $contentRights->GetMove();
                break;
            
            case BackendAction::Delete():
                $allowed = $contentRights->GetRemove();
                break;
            
            case BackendAction::Read():
                $allowed = $this->HasAnyContentRight($contentRights);
                break;
        }
        
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    
    /**
     * Grant access to create a new layout by common group settings
     * @return GrantResult
     */
    private function GrantCreateLayout()
    {
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            if ($group->GetCreateLayouts())
            {
                return GrantResult::Allowed();
            }
        }
        return GrantResult::NoAccess();
    }
    
    
    /**
     * Grant access to create a new site by common group settings
     * @return GrantResult
     */
    private function GrantCreateSite()
    {
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            if ($group->GetCreateSites())
            {
                return GrantResult::Allowed();
            }
        }
        return GrantResult::NoAccess();
    }
    
    
    /**
     * Grant access to create a new site by common group settings
     * @return GrantResult
     */
    private function GrantCreateContainer()
    {
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            if ($group->GetCreateContainers())
            {
                return GrantResult::Allowed();
            }
        }
        return GrantResult::NoAccess();
    }
    /**
     * Calculates the grant result for a content
     * @param Content $content The content
     * @param BackendAction $action The action that shall be taken on the content
     * @return GrantResult Returns the calculated grant result
     */
    private function GrantOnContent(Content $content, BackendAction $action)
    {
        if ($this->GetUser()->Equals($content->GetUser()))
        {
            return GrantResult::Allowed();
        }
        $contentRights = RightsFinder::FindContentRights($content);
        $contentGroup = GroupFinder::FindContentGroup($content);
        if (!$contentRights || !$contentGroup)
        {
            return GrantResult::NoAccess();
        }
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            $result = $this->GrantGroupOnContent($group, $contentGroup, $contentRights, $action);
            if ($result->Equals(GrantResult::Allowed()))
            {
                return $result;
            }
        }
        return GrantResult::NoAccess();
    }
    
    private function GrantOnContainer (Container $container, BackendAction $action)
    {   
        if ($this->GetUser()->Equals($container->GetUser()))
        {
            return GrantResult::Allowed();
        }
        if (!$container->GetUserGroup() || !$container->GetUserGroupRights())
        {
            return GrantResult::NoAccess();
        }
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            $result = $this->GrantGroupOnContainer($group, $container, $action);
            if ($result->Equals(GrantResult::Allowed()))
            {
                return $result;
            }
        }
        return GrantResult::NoAccess();
    }
    
    private function GrantOnPage(Page $page, BackendAction $action)
    {
        if ($this->GetUser()->Equals($page->GetUser()))
        {
            return GrantResult::Allowed();
        }
        $pageGroup = GroupFinder::FindPageGroup($page);
        $pageRights = RightsFinder::FindPageRights($page);
        if (!$pageGroup || !$pageRights)
        {
            return GrantResult::NoAccess();
        }
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            $result = $this->GrantGroupOnPage($group, $pageGroup, $pageRights, $action);
            if ($result->Equals(GrantResult::Allowed()))
            {
                return $result;
            }
        }
        return GrantResult::NoAccess();
    }
    private function GrantOnSite(Site $site, BackendAction $action)
    {
        //Site user (creator) can do anything
        if ($this->GetUser()->Equals($site->GetUser()))
        {
            return GrantResult::Allowed();
        }
        if (!$site->GetUserGroup() || !$site->GetUserGroupRights())
        {
            return GrantResult::NoAccess();
        }
        $groups = $this->GetGroups();
        foreach ($groups as $group)
        {
            $result = $this->GrantGroupOnSite($group, $site, $action);
            
            if ($result->Equals(GrantResult::Allowed()))
            {
                return $result;
            }
        }
        return GrantResult::NoAccess();
    }

    private function GrantOnModule(ModuleBase $module)
    {
        $bundle = $module->MyBundle();
        $moduleName = $module->MyName();
        $inList = $this->GetGroupList();
        $tblModLock = ModuleLock::Schema()->Table();
        $sql = Access::SqlBuilder();
        $where = $sql->Equals($tblModLock->Field('Bundle'), $sql->Value($bundle))
                    ->And_($sql->In($tblModLock->Field('UserGroup'), $inList))
                    ->And_($sql->Equals($tblModLock->Field('Module'), $sql->Value(''))->
                        Or_($sql->Equals($tblModLock->Field('Module'), $sql->Value($moduleName))));
        
        if (ModuleLock::Schema()->Count(false, $where)  > 0)
        {
            return GrantResult::NoAccess();
        }
        return GrantResult::Allowed();
    }
    
    //Helper functions for easy access calculation
    
    /**
     * Grant evaluation for adding a page on top of the site
     * @param Site $site The site
     * @return GrantResult The result
     */
    function GrantAddPageToSite(Site $site)
    {
        //Dummy page for evaluation
        $page = new Page();
        $page->SetUserGroup($site->GetUserGroup());
        $siteRights = $site->GetUserGroupRights();
        if ($siteRights)
        {
            $page->SetUserGroupRights($siteRights->GetPageRights());
        }
        return $this->Grant(BackendAction::Create(), $page);
    }
    /**
     * Grant evaluation vor a page are
     * @param Page $page
     * @param Area $area
     * @return GrantResult
     */
    function GrantAddContentToPageArea(Page $page, Area $area)
    {
        $result = $this->Grant(BackendAction::Create(), $area);
        
        if (!$result->ToBool())
        {
            return $result;
        }
        return $this->GrantAddContentToPage($page);
    }
    /**
     * Grant evaluation for adding content on top of a page area
     * @param Page $page The page
     * @return GrantResult GrantResult Returns the grant result telling if creation is allowed
     */
    function GrantAddContentToPage(Page $page)
    {
        //dummy content for evaluation
        $content = new Content();
        $content->SetUserGroup(GroupFinder::FindPageGroup($page));
        $pageRights = RightsFinder::FindPageRights($page);
        if ($pageRights)
        {
            $content->SetUserGroupRights($pageRights->GetContentRights());
        }
        return $this->Grant(BackendAction::Create(), $content);
    }
    /**
     * Grant evaluation for adding content to layout area
     * @param Area $area
     * @return GrantResult
     */
    function GrantAddContentToLayoutArea(Area $area)
    {
        $result = $this->Grant(BackendAction::Create(), $area);
        if (!$result->ToBool())
        {
            return $result;
        }
        return $this->GrantAddContentToLayout($area->GetLayout());
    }
    /**
     * Grant evaluation for adding content on top of a layout area
     * @param Layout $layout The layout
     * @return GrantResult Returns the grant result telling if creation is allowed
     */
    function GrantAddContentToLayout(Layout $layout)
    {
        //dummy content for evaluation
        $content = new Content();
        $content->SetUserGroup($layout->GetUserGroup());
        $layoutRights = $layout->GetUserGroupRights();
        if ($layoutRights)
        {
            $content->SetUserGroupRights($layoutRights->GetContentRights());
        }
        return $this->Grant(BackendAction::Create(), $content);
    }
    private function GrantOnUser(BackendAction $action, User $user)
    {
        $allowed = false;
        switch ($action)
        {
            case BackendAction::Delete(): 
            case BackendAction::ChangeIsAdmin():
                $allowed = $this->IsAdministrator() && !$this->GetUser()->Equals($user);
                break;
            
            case BackendAction::AssignGroups():
                $allowed = $this->IsAdministrator() && !$user->GetIsAdmin();
                break;
               
            case BackendAction::Edit():
            case BackendAction::Read():
                $allowed = $this->IsAdministrator() || $this->GetUser()->Equals($user);
                break;
            case BackendAction::Create():
                $allowed = $this->IsAdministrator();
                break;
            
        }
        return $allowed ? GrantResult::Allowed() : GrantResult::NoAccess();
    }
    /**
     * Grant evaluation for adding content on top of a container
     * @param Container $container The container
     * @return GrantResult Returns the grant result telling if creation is allowed
     */
    function GrantAddContentToContainer(Container $container)
    {
        //dummy content for evaluation
        $content = new Content();
        $content->SetUserGroup($container->GetUserGroup());
        $containerRights = $container->GetUserGroupRights();
        if ($containerRights)
        {
            $content->SetUserGroupRights($containerRights->GetContentRights());
        }
        return $this->Grant(BackendAction::Create(), $content);
    }
}