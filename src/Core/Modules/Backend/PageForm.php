<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Database\Core\Site;
use Phine\Database\Core\Page;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Tree\TreeBuilder;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use Phine\Database\Access;
use Phine\Database\Core\Layout;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Framework\System\IO\Path;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Routing\Rewriter;
use Phine\Framework\Webserver\Apache\Htaccess\Writer;
use Phine\Bundles\Core\Logic\DBEnums\MenuAccess;
use Phine\Bundles\Core\Logic\Access\Backend\GroupFinder;
use Phine\Bundles\Core\Logic\Access\Backend\RightsFinder;
use Phine\Database\Core\Usergroup;
use Phine\Bundles\Core\Snippets\BackendRights\PageRights;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Database\Core\PageMembergroup;
use Phine\Bundles\Core\Logic\Util\MembergroupUtil;
use Phine\Database\Core\Membergroup;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Framework\System\Date;
use Phine\Framework\Sitemap\Enums\ChangeFrequency;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;
use Phine\Bundles\Core\Snippets\FormParts\PageUrlSelector;
use Phine\Bundles\Core\Logic\DBEnums\PageType;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
/**
 * The page form
 */
class PageForm extends BackendForm
{
    
    /**
     * The site currently edited
     * @var Site
     */
    private $site;
    
    /**
     * The edited page
     * @var Page
     */
    private $page;
    
    /**
     * The parent page
     * @var Page
     */
    private $parent;
    
    /**
     * The previous page
     * @var Page
     */
    private $previous;
    
    /**
     * The page rights snippet 
     * @var PageRights
     */
    protected $pageRights;
    
    /**
     * The localized date format used for the date pickers
     * @var string
     */
    private $dateFormat;
    
    /**
     * True if member groups exist
     * @var boolean
     */
    protected $hasMemberGroups;
    
    /**
     * A page url selector for redirect pages
     * @var 
     */
    protected $selector;
    /**
     * Initializes the form
     * @return boolean
     */
    protected function Init()
    {
        $this->page = new Page(Request::GetData('page'));
        $this->parent = Page::Schema()->ByID(Request::GetData('parent'));
        $this->previous = Page::Schema()->ByID(Request::GetData('previous'));
        $this->site = $this->page->Exists() ? $this->page->GetSite() : 
            Site::Schema()->ByID(Request::GetData('site'));
        
        $this->dateFormat = Trans('Core.DateFormat');
        $this->InitPageRights();
        
        if (!$this->page->Exists() && !$this->site->Exists())
        {
            Response::Redirect(BackendRouter::ModuleUrl(new SiteList()));
            return true;
        }
        $this->AddLayoutField();
        $this->AddNameField();
        $this->AddUrlField();
        $this->AddTitleField();
        $this->AddDescriptionField();
        $this->AddKeywordsField();
        $this->AddMenuAccessField();
        $this->AddUserGroupField();
        $this->AddGuestsOnlyField();
        $this->AddMemberGroupField();
        $this->AddPublishField();
        $this->AddSubmit();
        $this->AddPublishFromDateField();
        $this->AddPublishFromHourField();
        $this->AddPublishFromMinuteField();
        $this->AddPublishToDateField();
        $this->AddPublishToHourField();
        $this->AddPublishToMinuteField();
        $this->AddSitemapRelevanceField();        
        $this->AddSitemapChangeFrequencyField();
        $this->AddTypeField();
        $this->AddRedirectTargetSelector();
        return parent::Init();
    }
    private function AddGuestsOnlyField()
    {
        $name = 'GuestsOnly';
        $field = new Checkbox($name, '1', (bool)$this->page->GetGuestsOnly());
        $this->AddField($field);
    }
    
    private function AddMemberGroupField()
    {
        $name = 'MemberGroup';
        $field = MembergroupUtil::PageCheckList($name, $this->page);
        $this->hasMemberGroups = count($field->GetOptions()) > 0;
        if ($this->hasMemberGroups)
        {
            $field->SetHtmlAttribute('id', $name);
            $this->AddField($field);
        }
    }
    
    /**
     *  Finds the parent group
     *  @return Usergroup
     */
    private function FindParentGroup()
    {
        $parentGroup = null;
        if ($this->parent)
        {
            $parentGroup = GroupFinder::FindPageGroup($this->parent);
        }
        if (!$parentGroup)
        {
            $parentGroup = $this->site->GetUserGroup();
        }
        return $parentGroup;
    }
    
    /**
     * Adds the user group field
     */
    private function AddUserGroupField()
    {
        $name = 'UserGroup';
        $field = new Select($name, '');
        
        $parentGroup = $this->FindParentGroup();
        $inheritText = $parentGroup ? Trans('Core.PageForm.UserGroup.Inherit_{0}', $parentGroup->GetName()) :
                Trans('Core.PageForm.UserGroup.Inherit');
        
        $field->AddOption('', $inheritText);
        if ($this->page->Exists() && $this->page->GetUserGroup())
        {
            $field->SetValue($this->page->GetUserGroup()->GetID());
        }
        DBSelectUtil::AddUserGroupOptions($field);
        $this->AddField($field);
    }
    
    /**
     * 
     * 
     * @return PageRights
     */
    protected function InitPageRights()
    {
        $rights = $this->page->Exists() ? $this->page->GetUserGroupRights() : null;
        $parentRights = null;
        if ($this->parent)
        {
            $parentRights = RightsFinder::FindPageRights($this->parent);
        }
        if (!$parentRights)
        {
            $siteRights = $this->site->GetUserGroupRights();
            if ($siteRights)
            {
                $parentRights = $this->site->GetUserGroupRights()->GetPageRights();
            }
        }
        $this->pageRights = new PageRights($parentRights, $rights);
    }
    /**
     * Adds name field to the form
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->page->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueFieldAnd($this->page, $name, $this->SiteCondition()));
    }
    
    private function SiteCondition()
    {
        $sql = Access::SqlBuilder();
        $tbl = Page::Schema()->Table();
        return $sql->Equals($tbl->Field('Site'), $sql->Value($this->site->GetID()));
    }
    
    /**
     * Adds the url field to the form
     */
    private function AddUrlField()
    {
        $name = 'Url';
        $this->AddField(Input::Text($name, $this->page->GetUrl()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueFieldAnd($this->page, $name, $this->SiteCondition()));
    }
    
    /**
     * Adds the title field to the form
     */
    private function AddTitleField()
    {
        $name = 'Title';
        $this->AddField(Input::Text($name, $this->page->GetTitle()));
    }
    
    /**
     * Adds the description field to the form
     */
    private function AddDescriptionField()
    {
        $name = 'Description';
        $this->AddField(Input::Text($name, $this->page->GetDescription()));
    }
    
    /**
     * Adds the keywords field to the form
     */
    private function AddKeywordsField()
    {
        $name = 'Keywords';
        $this->AddField(Input::Text($name, $this->page->GetKeywords()));
    }
    
    /**
     * Adds the layout field to the form
     */
    private function AddLayoutField()
    {
        $name = 'Layout';
        $select = new Select($name);
        if ($this->page->Exists())
        {
            $select->SetValue($this->page->GetLayout()->GetID());
        }
        
        $select->AddOption('', Trans('Core.PleaseSelect'));
        $sql = Access::SqlBuilder();
        $tbl = Layout::Schema()->Table();
        $order = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $layouts = Layout::Schema()->Fetch(false, null, $order);
        
        foreach ($layouts as $layout)
        {
            $select->AddOption($layout->GetID(), $layout->GetName());
        }
        $this->AddField($select);
        $this->SetRequired($name);
    }
    
    
    /**
     * Adds the menu access select
     */
    private function AddMenuAccessField()
    {
        $name = 'MenuAccess';
        $value = $this->page->Exists() ? $this->page->GetMenuAccess() : (string)MenuAccess::Authorized();
        $select = new Select($name, $value);
        foreach (MenuAccess::AllowedValues() as $access)
        {
            $select->AddOption($access, Trans("Core.PageForm.$name.$access"));
        }
        $this->AddField($select);
        $this->SetRequired($name);
    }
    
    
    /**
     * Adds the publish check box
     */
    private function AddPublishField()
    {
        $name = 'Publish';
        $field = new Checkbox($name, '1', (bool)$this->page->GetPublish());
        $this->AddField($field);
    }
    
    
    /**
     * Adds the publish from date field
     */
    private function AddPublishFromDateField()
    {
        $name = 'PublishFromDate';
        $from = $this->page->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString($this->dateFormat) : '');
        $field->SetHtmlAttribute('data-type', 'date');
        $this->AddField($field);
    }
    
    
    /**
     * Adds the publish from hour field
     */
    private function AddPublishFromHourField()
    {
        $name = 'PublishFromHour';
        $from = $this->page->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString('H') : '');
        $field->SetHtmlAttribute('data-type', 'hour');
        $this->AddField($field);
    }
    
    
    /**
     * Adds the publish from minute field
     */
    private function AddPublishFromMinuteField()
    {
        $name = 'PublishFromMinute';
        $from = $this->page->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString('i') : '');
        $field->SetHtmlAttribute('data-type', 'minute');
        $this->AddField($field);
    }
    
    /**
     * Adds the publish to date field
     */
    private function AddPublishToDateField()
    {
        $name = 'PublishToDate';
        $to = $this->page->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString($this->dateFormat) : '');
        $field->SetHtmlAttribute('data-type', 'date');
        $this->AddField($field);
    }
    /**
     * Adds the publish to hour field
     */
    private function AddPublishToHourField()
    {
        $name = 'PublishToHour';
        $to = $this->page->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString('H') : '');
        $field->SetHtmlAttribute('data-type', 'hour');
        $this->AddField($field);
    }
    
    
    private function AddPublishToMinuteField()
    {
        $name = 'PublishToMinute';
        $to = $this->page->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString('i') : '');
        $field->SetHtmlAttribute('data-type', 'minute');
        $this->AddField($field);
    }
    
    /**
     * Adds the sitemap relevance field
     */
    private function AddSitemapRelevanceField()
    {
        $name = 'SitemapRelevance';
        $value = $this->page->Exists() ? 10* $this->page->GetSitemapRelevance() : 7;
        $field = new Select($name, $value);
        for ($val = 0; $val <= 10; ++$val)
        {
            $decSep = Trans('Core.DecimalSeparator');
            $thousSep = Trans('Core.ThousandsSeparator');
            $text = number_format($val / 10, 1, $decSep, $thousSep);
            $field->AddOption($val, $text);
        }
        $this->AddField($field);
    }
    
    /**
     * Adds the sitemap change frequency field
     */
    private function AddSitemapChangeFrequencyField()
    {
        $name = 'SitemapChangeFrequency';
        $value = $this->page->Exists() ? $this->page->GetSitemapChangeFrequency() :
            (string)ChangeFrequency::Weekly();
        
        $field = new Select($name, $value);
        $values = ChangeFrequency::AllowedValues();
        foreach ($values as $val)
        {
            $field->AddOption($val, Trans('Core.Sitemap.ChangeFrequency.' . ucfirst($val)));
        }
        $this->AddField($field);   
    }
    private function AddTypeField()
    {
        $name = 'Type';
        $value = $this->page->Exists() ? $this->page->GetType() : (string)PageType::Normal();
        $field = new Select($name, $value);
        $types = PageType::AllowedValues();
        $ex404 = FrontendRouter::Page404($this->site);
        foreach ($types as $type)
        {
            if ($type == (string)PageType::NotFound() && 
                    $ex404 && !$ex404->Equals($this->page))
            {
                continue;
            }
            $field->AddOption($type, Trans('Core.PageForm.Type.' . ucfirst($type)));
        }
        $this->AddField($field);
    }
    private function AddRedirectTargetSelector()
    {
        $name = 'RedirectTarget';
        $this->selector = new PageUrlSelector($name, Trans($this->Label($name)), $this->page->GetRedirectTarget());
        if ($this->page->Exists())
        {
            $this->selector->DisablePage($this->page);
        }
        if ($this->Value('Type') == (string)PageType::RedirectPermanent() || 
                $this->Value('Type') == (string)PageType::RedirectTemporary())
        {
            $this->selector->SetRequired($this->ErrorPrefix($name));
        }
        $this->Elements()->AddElement($name, $this->selector);
    }

    /**
     * Saves the page
     */
    protected function OnSuccess()
    {
        $this->page->SetName($this->Value('Name'));
        $this->page->SetUrl($this->Value('Url'));
        $this->page->SetSite($this->site);
        $this->page->SetTitle($this->Value('Title'));
        $this->page->SetDescription($this->Value('Description'));
        $this->page->SetKeywords($this->Value('Keywords'));
        $this->page->SetLayout(new Layout($this->Value('Layout')));
        $this->page->SetMenuAccess($this->Value('MenuAccess'));
        $this->page->SetGuestsOnly((bool)$this->Value('GuestsOnly'));
        $this->page->SetPublish((bool)$this->Value('Publish'));
        $this->page->SetPublishFrom($this->PublishDate('PublishFrom'));
        $this->page->SetPublishTo($this->PublishDate('PublishTo'));
        $relevance = (float)$this->Value('SitemapRelevance') / 10;
        $this->page->SetSitemapRelevance(min(max(0.0, $relevance), 1.0));
        $this->page->SetSitemapChangeFrequency($this->Value('SitemapChangeFrequency'));
        $this->SaveType();
        $action = Action::Update();
        if (!$this->page->Exists())
        {
            $action = Action::Create();
            $this->SaveNew();
        }
        else
        {
            $this->page->Save();
        }
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportPageAction($this->page, $action);
        if ($this->CanAssignGroup())
        {
            $this->SaveRights();
        }
        $this->SaveMemberGroups();
        $this->AdjustHtaccess();
        Response::Redirect($this->BackLink());
    }
    
    private function SaveType()
    {
        $type = PageType::ByValue($this->Value('Type'));
        $this->page->SetType((string)$type);
        $target = $this->page->GetRedirectTarget();
        switch ($type)
        {
            case PageType::Normal():
            case PageType::NotFound():
                if ($target)
                {
                    $this->page->SetRedirectTarget(null);
                    $target->Delete();
                }
                break;
            case PageType::RedirectPermanent():
            case PageType::RedirectTemporary():
                $this->page->SetRedirectTarget($this->selector->Save($target));
        }
    }
    /**
     * Gets a publishing date
     * @param string $baseName The base name; 'PublishFrom' or 'PublishTo'
     * @return Date Returns the date
     */
    private function PublishDate($baseName)
    {
        if (!$this->page->GetPublish())
        {
            return null;
        }
        $strDate = $this->Value($baseName . 'Date');
        if (!$strDate)
        {
            return null;
        }
        $date = \DateTime::createFromFormat($this->dateFormat, $strDate);
        $date->setTime((int)$this->Value($baseName . 'Hour'), (int)$this->Value($baseName . 'Minute'), 0);
        return Date::FromDateTime($date);
    }
    
    /**
     * True if user group can be assigned
     * @return bool
     */
    protected function CanAssignGroup()      
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->page);
    }
    
    /**
     * Saves the group and right settings
     */
    private function SaveRights()
    {
        $groupID = $this->Value('UserGroup');
        $userGroup = Usergroup::Schema()->ByID($groupID);
        $this->page->SetUserGroup($userGroup);
        if (!$userGroup)
        {
            $oldRights = $this->page->GetUserGroupRights();
            if ($oldRights)
            {
                $oldRights->GetContentRights()->Delete();
            }
            $this->page->SetUserGroupRights(null);
        }
        else
        {
            $this->pageRights->Save();
            $this->page->SetUserGroupRights($this->pageRights->Rights());
        }
        $this->page->Save();
    }
    
    /**
     * Saves the member groups
     */
    private function SaveMemberGroups()
    {
        $selectedIDs = Request::PostArray('MemberGroup');
        if ($this->page->GetGuestsOnly())
        {
            $selectedIDs = array();
        }
        $exIDs = Membergroup::GetKeyList(MembergroupUtil::PageMembergroups($this->page));
        $this->DeleteOldMemberGroups($selectedIDs);
        $this->SaveNewMemberGroups($selectedIDs, $exIDs);
    }
    
    /**
     * Deletes the old member groups
     * @param array $selectedIDs The selected member ids
     */
    private function DeleteOldMemberGroups(array $selectedIDs)
    {
        $sql = Access::SqlBuilder();
        $tblPgGrp = PageMembergroup::Schema()->Table();
        $where = $sql->Equals($tblPgGrp->Field('Page'), $sql->Value($this->page->GetID()));
        if (count($selectedIDs))
        {
            $inSelected = $sql->InListFromValues($selectedIDs);
            $where = $where->And_($sql->NotIn($tblPgGrp->Field('MemberGroup'), $inSelected));
        }
        PageMembergroup::Schema()->Delete($where);
    }
    
    /**
     * Saves page member groups not already assigned
     * @param array $selectedIDs The selected member group ids
     * @param array $exIDs The already assigned membergroup ids
     */
    private function SaveNewMemberGroups(array $selectedIDs, array $exIDs)
    {
        foreach ($selectedIDs as $selID)
        {
            if (!in_array($selID, $exIDs))
            {
                $pgGrp = new PageMembergroup();
                $pgGrp->SetPage($this->page);
                $pgGrp->SetMemberGroup(new Membergroup($selID));
                $pgGrp->Save();
            }
        }
        
    }
    
    /**
     * Takes care of page tree insertion important for a fresh page
     */
    private function SaveNew()
    {
        $treeBuilder = new TreeBuilder(new PageTreeProvider($this->site));
        $treeBuilder->Insert($this->page, $this->parent, $this->previous);
    }
    
    /**
     * Adds necessary rewrite commands
     */
    private function AdjustHtaccess()
    {
        $file = Path::Combine(PHINE_PATH, 'Public/.htaccess');
        if (!File::Exists($file))
        {
            return;
        }
        $writer = new Writer();
        $rewriter = new Rewriter($writer);
        $text = File::GetContents($file);
        $startPos = strpos($text, (string)$rewriter->PageStartComment($this->page));
        $endPos = false;
        $pageFound = false;
        if ($startPos === false)
        {
            $startPos = strpos($text, (string)$rewriter->EndComment());
            $endPos = $startPos;
        }
        else
        {
            $endPos = strpos($text, (string)$rewriter->PageEndComment($this->page));
            if ($endPos !== false)
            {
                $pageFound = true;
                $endPos += strlen((string)$rewriter->PageEndComment($this->page));
            }
        }
        if ($startPos === false || $endPos === false)
        {
            return;
        }
        $rewriter->AddPageCommands($this->page);
        $newText = substr($text, 0, $startPos) . $writer->ToString() . substr($text, $endPos);
        File::CreateWithText($file, $newText);
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the page tree
     */
    protected function BackLink()
    {
        $params = array('site'=>$this->site->GetID());
        if ($this->page->Exists())
        {
            $params['selected'] = $this->page->GetID();
        }
        else if ($this->previous)
        {
            $params['selected'] = $this->previous->GetID();
        }
        else if ($this->parent)
        {
            $params['selected'] = $this->parent->GetID();
        }
        return BackendRouter::ModuleUrl(new PageTree(), $params);
    }
}