<?php

namespace Phine\Bundles\Core\Logic\Logging;
use Phine\Framework\System\Date;
use Phine\Database\Core\Site;
use Phine\Database\Core\Page;
use Phine\Database\Core\Area;
use Phine\Database\Core\Layout;
use Phine\Database\Core\Content;
use Phine\Database\Core\Container;
use Phine\Database\Core\Member;
use Phine\Database\Core\Membergroup;
use Phine\Database\Core\User;
use Phine\Database\Core\Usergroup;
use Phine\Database\Core\LogItem;
use Phine\Database\Core\LogArea;
use Phine\Database\Core\LogContent;
use Phine\Database\Core\LogLayout;
use Phine\Database\Core\LogSite;
use Phine\Database\Core\LogPage;
use Phine\Database\Core\LogUser;
use Phine\Database\Core\LogUsergroup;
Use Phine\Database\Core\LogMember;
use Phine\Database\Core\LogMembergroup;
use Phine\Database\Core\LogContainer;
use Phine\Database\Core\LogTemplate;
use Phine\Database\Access;
use Phine\Bundles\Core\Logic\Config\SettingsProxy;

class Logger
{
    /**
     * The logged in user
     * @var User
     */
    private $user;
    
    /**
     * Creates a backend action logger
     * @param User $user The currently logged in user
     */
    function __construct(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Reports a site action to the log
     * @param Site $site The site being manipulated
     * @param Enums\Action $action The operation executed on the site
     */
    function ReportSiteAction(Site $site, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Site(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logSite = new LogSite();
            $logSite->SetLogItem($logItem);
            $logSite->SetSite($site);
            $logSite->Save();
        }
    }
    
    
    /**
     * Reports a page action with dependencies to the log
     * @param Page $page The page being manipulated
     * @param Enums\Action $action The operation executed on the page
     */
    function ReportPageAction(Page $page, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Page(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logPage = new LogPage();
            $logPage->SetLogItem($logItem);
            $logPage->SetPage($page);
            $logPage->Save();
        }
        else
        {
            $this->ReportSiteAction($page->GetSite(), Enums\Action::ChildDelete());
        }
    }
    
    /**
     * Reports an area action with dependencies to the log
     * @param Area $area The area being manipulated
     * @param Enums\Action $action The operation executed on the area
     */
    function ReportAreaAction(Area $area, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Area(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logArea = new LogArea();
            $logArea->SetLogItem($logItem);
            $logArea->SetArea($area);
            $logArea->Save();
        }
        else
        {
            $this->ReportLayoutAction($area->GetLayout(), Enums\Action::ChildDelete());
        }
    }
    
    
    /**
     * Reports a layout action with dependencies to the log
     * @param Layout $layout The layout being manipulated
     * @param Enums\Action $action The operation executed on the layout
     */
    function ReportLayoutAction(Layout $layout, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Layout(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logLayout = new LogLayout();
            $logLayout->SetLogItem($logItem);
            $logLayout->SetLayout($layout);
            $logLayout->Save();
        }
        else
        {
            //pages are deleted in cascade
            $pages = Page::Schema()->FetchByLayout(false, $layout);
            foreach ($pages as $page)
            {
                $this->ReportPageAction($page, Enums\Action::Delete());
            }
        }
    }
    
    /**
     * Reports a container action with dependencies to the log
     * @param Container $container The container being manipulated
     * @param Enums\Action $action The operation executed on the container
     */
    function ReportContainerAction(Container $container, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Container(), $action);
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logContainer = new LogContainer();
            $logContainer->SetContainer($container);
            $logContainer->SetLogItem($logItem);
            $logContainer->Save();
        }
        
    }
    
    /**
     * Reports a content action with dependencies to the log
     * @param Content $content The content being manipulated
     * @param Enums\Action $action The operation executed on the content
     */
    function ReportContentAction(Content $content, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Content(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logContent = new LogContent();
            $logContent->SetLogItem($logItem);
            $logContent->SetContent($content);
            $logContent->Save();
        }
        else
        {
            if ($content->GetContainerContent())
            {
                $this->ReportContainerAction($content->GetContainerContent()->GetContainer(), Enums\Action::ChildDelete());
            }
            else if ($content->GetPageContent())
            {
                $this->ReportPageAction($content->GetPageContent()->GetPage(), Enums\Action::ChildDelete());
            }
            else if ($content->GetLayoutContent())
            {
                $this->ReportAreaAction($content->GetLayoutContent()->GetArea(), Enums\Action::ChildDelete());
            }
        }
    }
    
    /**
     * Reports a member action to the log
     * @param Member $member The member being manipulated
     * @param Enums\Action $action The operation executed on the member
     */
    function ReportMemberAction(Member $member, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Member(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logMember = new LogMember();
            $logMember->SetLogItem($logItem);
            $logMember->SetMember($member);
            $logMember->Save();
        }
    }
    
    /**
     * Reports a member group action to the log
     * @param Membergroup $memberGroup The member group being manipulated
     * @param Enums\Action $action The operation executed on the member group
     */
    function ReportMemberGroupAction(Membergroup $memberGroup, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::MemberGroup(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logMemberGroup = new LogMembergroup();
            $logMemberGroup->SetLogItem($logItem);
            $logMemberGroup->SetMemberGroup($memberGroup);
            $logMemberGroup->Save();
        }
    }
    /**
     * Reports a user action to the log
     * @param User $user The user being manipulated
     * @param Enums\Action $action The operation executed on the user
     */
    function ReportUserAction(User $user, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::User(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logUser = new LogUser();
            $logUser->SetLogItem($logItem);
            $logUser->SetUser($user);
            $logUser->Save();
        }
    }
    
    /**
     * Reports a user group action to the log
     * @param Usergroup  $userGroup  The user group being manipulated
     * @param Enums\Action $action The operation executed on the user group 
     */
    function ReportUserGroupAction(Usergroup $userGroup, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::UserGroup(), $action);       
        if (!$action->Equals(Enums\Action::Delete()))
        {
            $logUserGroup = new LogUsergroup();
            $logUserGroup->SetLogItem($logItem);
            $logUserGroup->SetUserGroup($userGroup);
            $logUserGroup->Save();
        }
    }
    
    /**
     * Reports an action on a module template
     * @param string $moduleType The module type the template belongs to
     * @param string $template The template file name
     * @param Enums\Action $action The action
     */
    function ReportTemplateAction($moduleType, $template, Enums\Action $action)
    {
        $logItem = $this->CreateLogItem(Enums\ObjectType::Template(), $action);
        $logTemplate = new LogTemplate;
        $logTemplate->SetLogItem($logItem);
        $logTemplate->SetModuleType($moduleType);
        $logTemplate->SetTemplate($template);
        $logTemplate->Save();
    }
    
    /**
     * Creates a log item
     * @param Enums\ObjectType $objType The type of object being manipulated
     * @param Enums\Action $action The operation executed on the object
     * @return LogItem Returns a log item, freshly saved to the database
     */
    private function CreateLogItem(Enums\ObjectType $objType, Enums\Action $action)
    {
        $this->DeleteOldLogItems();
        $item = new LogItem();
        $item->SetChanged(Date::Now());
        $item->SetAction((string)$action);
        $item->SetObjectType((string)$objType);
        $item->SetUser($this->user);
        $item->Save();
        return $item;
    }
    
    /**
     * Deletes log items older then the given amount of days
     * @param int $days The days
     */
    private function DeleteOldLogItems()
    {
        $days = SettingsProxy::Singleton()->Settings()->GetLogLifetime();
        $deleteBefore = Date::Now();
        $deleteBefore->AddDays(-$days);
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $where = $sql->LT($tblLogItem->Field('Changed'), $sql->Value($deleteBefore));
        LogItem::Schema()->Delete($where);
    }
}

