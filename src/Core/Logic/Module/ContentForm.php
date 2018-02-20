<?php

namespace Phine\Bundles\Core\Logic\Module;

use Phine\Framework\Database\Objects\TableObject;
use Phine\Framework\Database\Objects\TableSchema;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Framework\System\IO\Folder;
use Phine\Framework\System\IO\Path;
use App\Phine\Database\Core\Content;
use Phine\Bundles\Core\Logic\Tree\LayoutContentTreeProvider;
use Phine\Bundles\Core\Logic\Tree\PageContentTreeProvider;
use Phine\Bundles\Core\Logic\Tree\ContainerContentTreeProvider;
use App\Phine\Database\Core\LayoutContent;
use App\Phine\Database\Core\PageContent;
use App\Phine\Database\Core\ContainerContent;
use Phine\Bundles\Core\Logic\Tree\TreeBuilder;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Area;
use App\Phine\Database\Core\Container;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Modules\Backend\Overview;
use Phine\Bundles\Core\Modules\Backend\PageContentTree;
use Phine\Bundles\Core\Modules\Backend\LayoutContentTree;
use Phine\Bundles\Core\Modules\Backend\ContainerContentTree;
use Phine\Bundles\Core\Logic\Module\FrontendModule;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Tree\ContentTreeUtil;
use Phine\Bundles\Core\Logic\Access\Backend\RightsFinder;
use Phine\Bundles\Core\Logic\Access\Backend\GroupFinder;
use App\Phine\Database\Core\Usergroup;
use App\Phine\Database\Core\BackendContentRights;
use Phine\Bundles\Core\Snippets\BackendRights\ContentRights;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use App\Phine\Database\Core\ContentWording;
use Phine\Framework\Validation\Integer;
use App\Phine\Database\Core\Membergroup;
use App\Phine\Database\Core\ContentMembergroup;
use Phine\Bundles\Core\Logic\Util\MembergroupUtil;
use App\Phine\Database\Access;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Framework\System\Date;
use Phine\Framework\System\Str;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * 
 * Represents a backend form for a frontend (content) module
 */
abstract class ContentForm extends BackendForm
{

    /**
     * @var Content
     */
    private $content;

    /**
     * Initialize the form here
     */
    protected abstract function InitForm();

    /**
     * Gets the schema of the content element (or null if there is none)
     * @return TableSchema
     */
    protected abstract function ElementSchema();

    /**
     * In derived classes, this function saves the content element and returns it
     * @return TableObject Returns the saved element (or null if there is none)
     */
    protected abstract function SaveElement();

    /**
     * The related frontend module
     * @return FrontendModule
     */
    protected abstract function FrontendModule();

    /**
     * The content rights snippet
     * @var ContentRights
     */
    protected $contentRights;

    /**
     * True if member groups exist
     * @var boolean
     */
    private $hasMemberGroups = false;

    /**
     * The local date format
     * @var string
     */
    private $dateFormat;

    /**
     * 
     * @return Content
     */
    private function Content()
    {
        if (!$this->content) {
            $this->content = new Content(Request::GetData('content'));
        }
        return $this->content;
    }
    
    /**
     * Can be used in derived classes to publish contents without corresponding form fields
     * @return boolean
     */
    protected function AutoPublish()
    {
        return false;
    }

    /**
     *
     * @var Area
     */
    private $area;

    /**
     * The area from request
     * @return Area
     */
    protected function Area()
    {
        if (!$this->area) {
            $this->area = new Area(Request::GetData('area'));
        }
        return $this->area;
    }

    /**
     *
     * @var Container
     */
    private $container;

    /**
     * The container from request
     * @return Container
     */
    protected function Container()
    {
        if (!$this->container) {
            $this->container = new Container(Request::GetData('container'));
        }
        return $this->container;
    }

    /**
     * The parent page
     * @var Page
     */
    private $page;

    /**
     * The page from request
     * @return Page
     */
    protected function Page()
    {
        if (!$this->page) {
            $this->page = new Page(Request::GetData('page'));
        }
        return $this->page;
    }

    private $parent;

    /**
     * Gets the parent tree item
     * @return TableObject Returns either the related page or the layout content
     */
    private function ParentItem()
    {
        $this->parent = $this->GetTreeItem('parent');
        return $this->parent->Exists() ? $this->parent : null;
    }

    private function GetTreeItem($requestParam)
    {
        switch ($this->Location()) {
            case Enums\ContentLocation::Layout():
                $result = new LayoutContent(Request::GetData($requestParam));
                break;

            case Enums\ContentLocation::Page():
                $result = new PageContent(Request::GetData($requestParam));
                break;

            case Enums\ContentLocation::Container():
                $result = new ContainerContent(Request::GetData($requestParam));
                break;
        }
        return $result;
    }

    /**
     * The previous item in the content tree
     * @var TemplateObject
     */
    private $previous;

    /**
     * The previous item
     * @return TemplateObject
     */
    private function PreviousItem()
    {
        $this->previous = $this->GetTreeItem('previous');
        return $this->previous->Exists() ? $this->previous : null;
    }

    /**
     * Gets the content location
     * @return Enums\ContentLocation
     */
    protected function Location()
    {
        if ($this->Container()->Exists()) {
            return Enums\ContentLocation::Container();
        }
        else if ($this->Area()->Exists()) {
            if ($this->Page()->Exists()) {
                return Enums\ContentLocation::Page();
            }
            return Enums\ContentLocation::Layout();
        }
        return null;
    }

    /**
     * Initializes the module
     * @return boolean Trur if processing shall consider
     */
    protected final function Init()
    {
        if (!$this->Location()) {
            //TODO: Error
            Response::Redirect(BackendRouter::ModuleUrl(new Overview()));
        }
        $this->dateFormat = Trans('Core.DateFormat');
        $this->AddUserGroupField();
        $this->AddGuestsOnlyField();
        $this->AddMemberGroupField();
        $this->AddPublishField();
        $this->AddPublishFromDateField();
        $this->AddPublishFromHourField();
        $this->AddPublishFromMinuteField();
        $this->AddPublishToDateField();
        $this->AddPublishToHourField();
        $this->AddPublishToMinuteField();
        $this->AddWordingFields();


        $this->InitContentRights();
        return $this->InitForm();
    }

    /**
     * Initialize the content rights snippet
     */
    private function InitContentRights()
    {
        $beContentRights = null;
        if ($this->Content()->Exists()) {
            $beContentRights = $this->Content()->GetUserGroupRights();
        }
        $this->contentRights = new ContentRights($this->FindParentRights(), $beContentRights);
    }

    /**
     *  Finds the parent group
     *  @return Usergroup
     */
    private function FindParentGroup()
    {
        $parentGroup = null;
        $parentContent = $this->Content()->Exists() ? ContentTreeUtil::ParentOf($this->Content()) : null;
        if ($parentContent) {
            $parentGroup = GroupFinder::FindContentGroup($parentContent);
        }
        if (!$parentGroup) {
            switch ($this->Location()) {
                case Enums\ContentLocation::Page():
                    $parentGroup = GroupFinder::FindPageGroup($this->Page());
                    break;

                case Enums\ContentLocation::Layout():
                    $parentGroup = $this->Area()->GetLayout()->GetUserGroup();
                    break;

                case Enums\ContentLocation::Container():
                    $parentGroup = $this->Container()->GetUserGroup();
                    break;
            }
        }
        return $parentGroup;
    }

    /**
     *  Finds the parent rights
     *  @return BackendContentRights
     */
    private function FindParentRights()
    {
        $parentRights = null;
        $parentContent = $this->Content()->Exists() ? ContentTreeUtil::ParentOf($this->Content()) : null;
        if ($parentContent) {
            $parentRights = RightsFinder::FindContentRights($parentContent);
        }
        if (!$parentRights) {
            switch ($this->Location()) {
                case Enums\ContentLocation::Page():
                    $pageRights = RightsFinder::FindPageRights($this->Page());
                    $parentRights = $pageRights ? $pageRights->GetContentRights() : null;
                    break;

                case Enums\ContentLocation::Layout():
                    $layoutRights = $this->Area()->GetLayout()->GetUserGroupRights();
                    $parentRights = $layoutRights ? $layoutRights->GetContentRights() : null;
                    break;

                case Enums\ContentLocation::Container():
                    $containerRights = $this->Container()->GetUserGroupRights();
                    $parentRights = $containerRights ? $containerRights->GetContentRights() : null;
                    break;
            }
        }
        return $parentRights;
    }

    /**
     * Renders the group rights 
     * @return string
     */
    protected function RenderGroupRights()
    {
        if (!$this->CanAssignGroup()) {
            return '';
        }
        $result = $this->RenderElement('UserGroup');
        return $result . '<div id="group-rights">' . $this->contentRights->Render() . '</div>';
    }

    protected function RenderAccessElements($noFieldset = false)
    {
        $result = $this->RenderElement('Publish') .
                '<div class="row" id="PublishTimes"><div class="medium-6 columns">' . $this->RenderDateTimeFields('PublishFrom') . '</div>' .
                '<div class="medium-6 columns">' . $this->RenderDateTimeFields('PublishTo') . '</div></div>' .
                $this->RenderElement('GuestsOnly') . $this->RenderMemberGroups() .
                $this->RenderGroupRights();
        if (!$noFieldset) {
            $legend = Html(Trans('Core.ContentForm.Legend.Access'));
            $result = "<fieldset><legend>$legend</legend>$result</fieldset>";
        }
        return $result;
    }

    private function RenderDateTimeFields($baseName)
    {
        return '<div class="row">' .
                '<div class="medium-6 columns">' . $this->RenderElement($baseName . 'Date') . '</div>' .
                '<div class="medium-3 columns">' . $this->RenderElement($baseName . 'Hour') . '</div>' .
                '<div class="medium-3 columns">' . $this->RenderElement($baseName . 'Minute') . '</div>' .
                '</div>';
    }

    /**
     * 
     * Renders the frontend member groups
     * @return string
     */
    private function RenderMemberGroups()
    {
        if ($this->hasMemberGroups) {
            return $this->RenderElement('MemberGroup');
        }
        return '';
    }

    /**
     * Adds the user group field
     */
    private function AddUserGroupField()
    {
        $name = 'UserGroup';
        $field = new Select($name, '');

        if ($this->Content()->Exists() && $this->Content()->GetUserGroup()) {
            $field->SetValue($this->Content()->GetUserGroup()->GetID());
        }

        $parentGroup = $this->FindParentGroup();
        $inheritGroupText = $parentGroup ? Trans('Core.ContentForm.UserGroup.Inherit_{0}', $parentGroup->GetName()) :
                Trans('Core.ContentForm.UserGroup.Inherit');

        $field->AddOption('', $inheritGroupText);

        DBSelectUtil::AddUserGroupOptions($field);
        $this->AddField($field, false, Trans('Core.ContentForm.UserGroup'));
    }

    /**
     * Adds the checkbox for "guests only" visibility of a content
     */
    private function AddGuestsOnlyField()
    {
        $field = new Checkbox('GuestsOnly', '1', (bool) $this->Content()->GetGuestsOnly());
        $this->AddField($field, false, Trans('Core.ContentForm.GuestsOnly'));
    }

    /**
     * Helper function to retrieve the content element from database
     * @return TableObject Returns the content element by content if given
     */
    protected function LoadElement()
    {
        if ($this->Content()->Exists()) {
            return $this->ElementSchema()->ByContent($this->Content());
        }
        return $this->ElementSchema()->CreateInstance();
    }

    protected final function OnSuccess()
    {
        $isNew = !$this->Content()->Exists();
        if ($this->BeforeSave()) {
            return;
        }
        $this->SaveContent();
        $this->AttachContent($isNew);

        $element = $this->SaveElement();
        if ($element) {
            $element->SetContent($this->Content());
            $element->Save();
        }
        $this->AfterSave();
    }

    /**
     * Gives derived classes chance to stop processing before saving 
     * @return boolean Return true if processing shall break before saving
     */
    protected function BeforeSave()
    {
        return false;
    }

    /**
     * Saves content base properties
     */
    private function SaveContent()
    {
        $this->Content()->SetType($this->FrontendModule()->MyType());
        $this->Content()->SetCssClass($this->Value('CssClass'));
        $this->Content()->SetCssID($this->Value('CssID'));
        $this->Content()->SetTemplate($this->Value('Template'));
        $this->Content()->SetCacheLifetime((int) $this->Value('CacheLifetime'));
        $this->Content()->SetGuestsOnly((bool) $this->Value('GuestsOnly'));
        if ($this->AutoPublish())
        {
            $this->Content()->SetPublish(true);
        }
        else
        {
            $this->Content()->SetPublish((bool) $this->Value('Publish'));
        }
        $this->Content()->SetPublishFrom($this->PublishDate('PublishFrom'));
        $this->Content()->SetPublishTo($this->PublishDate('PublishTo'));

        $action = Action::Update();
        if (!$this->Content()->Exists()) {
            $action = Action::Create();
            $this->Content()->SetUser(self::Guard()->GetUser());
        }
        $this->Content()->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportContentAction($this->Content(), $action);
        if ($this->CanAssignGroup()) {
            $this->SaveRights();
        }
        $this->SaveMemberGroups();
        $this->SaveWordings();
    }

    private function SaveMemberGroups()
    {
        $selectedIDs = Request::PostArray('MemberGroup');
        if ($this->Content()->GetGuestsOnly()) {
            $selectedIDs = array();
        }
        $exIDs = Membergroup::GetKeyList(MembergroupUtil::ContentMembergroups($this->Content()));
        $this->DeleteOldMemberGroups($selectedIDs);
        $this->SaveNewMemberGroups($selectedIDs, $exIDs);
    }

    /**
     * Gets a publishing date
     * @param string $baseName The base name; 'PublishFrom' or 'PublishTo'
     * @return Date Returns the date
     */
    private function PublishDate($baseName)
    {
        if (!$this->Content()->GetPublish()) {
            return null;
        }
        $strDate = $this->Value($baseName . 'Date');
        if (!$strDate) {
            return null;
        }
        $date = \DateTime::createFromFormat($this->dateFormat, $strDate);
        $date->setTime((int) $this->Value($baseName . 'Hour'), (int) $this->Value($baseName . 'Minute'), 0);
        return Date::FromDateTime($date);
    }

    private function DeleteOldMemberGroups(array $selectedIDs)
    {
        $sql = Access::SqlBuilder();
        $tblContGrp = ContentMembergroup::Schema()->Table();
        $where = $sql->Equals($tblContGrp->Field('Content'), $sql->Value($this->Content()->GetID()));
        if (count($selectedIDs)) {
            $inSelected = $sql->InListFromValues($selectedIDs);
            $where = $where->And_($sql->NotIn($tblContGrp->Field('MemberGroup'), $inSelected));
        }
        ContentMembergroup::Schema()->Delete($where);
    }

    private function SaveNewMemberGroups(array $selectedIDs, array $exIDs)
    {
        foreach ($selectedIDs as $selID) {
            if (!in_array($selID, $exIDs)) {
                $cntMg = new ContentMembergroup();
                $cntMg->SetContent($this->Content());
                $cntMg->SetMemberGroup(new Membergroup($selID));
                $cntMg->Save();
            }
        }
    }

    private function CanAssignGroup()
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->Content());
    }

    /**
     * Saves the content rights and user group
     */
    private function SaveRights()
    {
        $userGroup = Usergroup::Schema()->ByID($this->Value('UserGroup'));
        $this->Content()->SetUserGroup($userGroup);
        if (!$userGroup) {
            $oldRights = $this->Content()->GetUserGroupRights();
            if ($oldRights) {
                $oldRights->Delete();
            }
            $this->Content()->SetUserGroupRights(null);
        }
        else {
            $this->contentRights->Save();
            $this->Content()->SetUserGroupRights($this->contentRights->Rights());
        }
        $this->Content()->Save();
    }

    /**
     * Called after all saving is performed
     */
    protected function AfterSave()
    {
        Response::Redirect($this->BackLink());
    }

    /**
     * Attaches content to tree item
     * @param boolean $isNew True if content is new
     */
    private function AttachContent($isNew)
    {
        switch ($this->Location()) {
            case Enums\ContentLocation::Layout():
                $this->AttachLayoutContent($isNew);
                break;

            case Enums\ContentLocation::Page():
                $this->AttachPageContent($isNew);
                break;


            case Enums\ContentLocation::Container():
                $this->AttachContainerContent($isNew);
                break;
        }
    }

    private function AttachLayoutContent($isNew)
    {
        $provider = new LayoutContentTreeProvider($this->Area());
        $tree = new TreeBuilder($provider);
        $layoutContent = $this->Content()->GetLayoutContent();
        if (!$layoutContent) {
            $layoutContent = new LayoutContent();
            $layoutContent->SetArea($this->Area());
            $layoutContent->SetContent($this->Content());
        }
        $provider->AttachContent($layoutContent, $this->Content());
        if ($isNew) {
            $tree->Insert($layoutContent, $this->ParentItem(), $this->PreviousItem());
        }
    }

    private function AttachPageContent($isNew)
    {
        $provider = new PageContentTreeProvider($this->Page(), $this->Area());
        $tree = new TreeBuilder($provider);
        $pageContent = $this->Content()->GetPageContent();
        if (!$pageContent) {
            $pageContent = new PageContent();
            $pageContent->SetArea($this->Area());
            $pageContent->SetPage($this->Page());
            $provider->AttachContent($pageContent, $this->Content());
        }
        if ($isNew) {
            $tree->Insert($pageContent, $this->ParentItem(), $this->PreviousItem());
        }
    }

    /**
     * Attaches the content to the container
     * @param boolean $isNew
     */
    private function AttachContainerContent($isNew)
    {
        $provider = new ContainerContentTreeProvider($this->Container());
        $tree = new TreeBuilder($provider);
        $containerContent = $this->Content()->GetContainerContent();
        if (!$containerContent) {
            $containerContent = new ContainerContent();
            $containerContent->SetContainer($this->Container());
            $provider->AttachContent($containerContent, $this->Content());
        }
        if ($isNew) {
            $tree->Insert($containerContent, $this->ParentItem(), $this->PreviousItem());
        }
    }

    /**
     * Adds the css class(es) form field
     */
    protected final function AddCssClassField()
    {
        $name = 'CssClass';
        $this->AddField(Input::Text($name, $this->Content()->GetCssClass()), false, Trans("Core.ContentForm.$name"));
    }

    /**
     * Adds the css id form field
     */
    protected final function AddCssIDField()
    {
        $name = 'CssID';
        $this->AddField(Input::Text($name, $this->Content()->GetCssID()), false, Trans("Core.ContentForm.$name"));
    }

    private function AddMemberGroupField()
    {
        $name = 'MemberGroup';
        $field = MembergroupUtil::ContentCheckList($name, $this->Content());
        $this->hasMemberGroups = count($field->GetOptions()) > 0;
        if ($this->hasMemberGroups) {
            $field->SetHtmlAttribute('id', $name);
            $this->AddField($field, false, Trans("Core.ContentForm.$name"));
        }
    }

    private function AddPublishField()
    {
        $name = 'Publish';
        $field = new Checkbox($name, '1', (bool) $this->Content()->GetPublish());
        $this->AddField($field, false, Trans('Core.ContentForm.Publish'));
    }

    private function AddPublishFromDateField()
    {
        $name = 'PublishFromDate';
        $from = $this->Content()->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString($this->dateFormat) : '');
        $field->SetHtmlAttribute('data-type', 'date');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishFromDate'));
    }

    private function AddPublishFromHourField()
    {
        $name = 'PublishFromHour';
        $from = $this->Content()->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString('H') : '');
        $field->SetHtmlAttribute('data-type', 'hour');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishFromHour'));
    }

    private function AddPublishFromMinuteField()
    {
        $name = 'PublishFromMinute';
        $from = $this->Content()->GetPublishFrom();
        $field = Input::Text($name, $from ? $from->ToString('i') : '');
        $field->SetHtmlAttribute('data-type', 'minute');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishFromMinute'));
    }

    private function AddPublishToDateField()
    {
        $name = 'PublishToDate';
        $to = $this->Content()->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString($this->dateFormat) : '');
        $field->SetHtmlAttribute('data-type', 'date');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishToDate'));
    }

    private function AddPublishToHourField()
    {
        $name = 'PublishToHour';
        $to = $this->Content()->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString('H') : '');
        $field->SetHtmlAttribute('data-type', 'hour');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishToHour'));
    }

    private function AddPublishToMinuteField()
    {
        $name = 'PublishToMinute';
        $to = $this->Content()->GetPublishTo();
        $field = Input::Text($name, $to ? $to->ToString('i') : '');
        $field->SetHtmlAttribute('data-type', 'minute');
        $this->AddField($field, false, Trans('Core.ContentForm.PublishToMinute'));
    }

    /**
     * Adds the lifetime field
     */
    protected function AddCacheLifetimeField()
    {
        $name = 'CacheLifetime';
        $label = "Core.ContentForm.$name";
        $field = Input::Text($name, (string) (int) $this->Content()->GetCacheLifetime());
        $this->AddField($field, false, Trans($label));
        $this->AddValidator($name, Integer::PositiveOrNull(), $label . '.');
    }

    /**
     * Adds the template select field
     * @param FrontendModule $module The module for template selection
     */
    protected final function AddTemplateField()
    {
        $name = 'Template';
        $field = new Select($name, (string) $this->Content()->GetTemplate());
        $field->AddOption('', Trans("Core.ContentForm.$name.Default"));

        $folder = PathUtil::ModuleCustomTemplatesFolder($this->FrontendModule());
        if (Folder::Exists($folder)) {
            $files = Folder::GetFiles($folder);
            foreach ($files as $file) {
                $value = Path::FilenameNoExtension($file);
                $field->AddOption($value, $value);
            }
        }
        $this->AddField($field, false, Trans("Core.ContentForm.$name"));
    }

    /**
     * Returns -1; content forms never appear in side navigation
     * @return int
     */
    final function SideNavIndex()
    {
        return parent::SideNavIndex();
    }

    private function SelectedTreeID()
    {
        if ($this->Content() && $this->Content()->Exists()) {
            switch ($this->Location()) {
                case Enums\ContentLocation::Page():
                    return $this->Content()->GetPageContent()->GetID();

                case Enums\ContentLocation::Layout():
                    return $this->Content()->GetLayoutContent()->GetID();

                case Enums\ContentLocation::Container():
                    return $this->Content()->GetContainerContent()->GetID();
            }
        }
        else if ($this->PreviousItem()) {
            return $this->PreviousItem()->GetID();
        }
        else if ($this->ParentItem()) {
            return $this->ParentItem()->GetID();
        }
        return '';
    }

    /**
     * The back link url
     * @return string
     */
    protected function BackLink()
    {
        $selected = $this->SelectedTreeID();
        switch ($this->Location()) {
            case Enums\ContentLocation::Page():
                return $this->PageBackLink($selected);

            case Enums\ContentLocation::Layout():
                return $this->LayoutBackLink($selected);

            case Enums\ContentLocation::Container():
                return $this->ContainerBackLink($selected);
        }
    }

    private function PageBackLink($selected)
    {
        $params = array();
        $params['page'] = $this->Page()->GetID();
        $params['area'] = $this->Area()->GetID();
        if ($selected) {
            $params['selected'] = $selected;
        }
        return BackendRouter::ModuleUrl(new PageContentTree(), $params);
    }

    private function LayoutBackLink($selected)
    {
        $params = array();
        $params['area'] = $this->Area()->GetID();
        if ($selected) {
            $params['selected'] = $selected;
        }
        return BackendRouter::ModuleUrl(new LayoutContentTree(), $params);
    }

    private function ContainerBackLink($selected)
    {
        $params = array();
        $params['container'] = $this->Container()->GetID();
        if ($selected) {
            $params['selected'] = $selected;
        }
        return BackendRouter::ModuleUrl(new ContainerContentTree(), $params);
    }

    /**
     * Adds the wording fields
     */
    private function AddWordingFields()
    {
        foreach ($this->Wordings() as $name) {
            $wording = $this->FindWording($name);
            $fieldName = $this->WordingFieldName($name);
            $field = Input::Text($fieldName, $wording ? $wording->GetText() : '');
            $field->SetHtmlAttribute('placeholder', Trans('Core.ContentForm.Wording.Placeholder'));
            $this->AddField($field);
        }
    }

    private function WordingFieldName($name)
    {
        return Str::Replace('.', '-', $name);
    }

    private function SaveWordings()
    {
        foreach ($this->Wordings() as $name) {
            $wording = $this->FindWording($name);
            $value = $this->Value($this->WordingFieldName($name));
            if (!$value) {
                if ($wording) {
                    $wording->Delete();
                }
                continue;
            }

            if (!$wording) {
                $wording = new ContentWording();
            }
            $wording->SetContent($this->Content());
            $wording->SetPlaceholder($name);
            $wording->SetText($value);
            $wording->Save();
        }
    }

    protected function RenderWordingFields($noFieldset = false)
    {
        $result = '';
        $wordings = $this->Wordings();
        if (count($wordings) == 0) {
            return $result;
        }
        if (!$noFieldset) {
            $legend = Html(Trans('Core.ContentForm.Legend.Wordings'));
            $result .= "<fieldset><legend>$legend</legend>";
        }
        $result .= '<div id="wording-table">';
        foreach ($this->Wordings() as $name) {
            $result .= '<div class="wording-row">';
            $result .= $this->RenderElement($this->WordingFieldName($name));
            $result .= '</div>';
        }
        $result .= '</div>';
        if (!$noFieldset) {
            $result .= '</fieldset>';
        }
        return $result;
    }

    /**
     * Finds the wording with the given placeholder name
     * @param string $name The placeholder name
     * @return ContentWording Gets the matching content wording
     */
    private function FindWording($name)
    {
        if (!$this->Content()->Exists()) {
            return null;
        }
        $sql = Access::SqlBuilder();
        $tblWording = ContentWording::Schema()->Table();
        $where = $sql->Equals($tblWording->Field('Content'), $sql->Value($this->Content()->GetID()))
                ->And_($sql->Equals($tblWording->Field('Placeholder'), $sql->Value($name)));

        return ContentWording::Schema()->First($where);
    }

    /**
     * Can be used by derived classes to provide wordings
     * @return string[] Returns a list of wording placeholders that can be customized with current the content element
     */
    protected function Wordings()
    {
        return array();
    }

}
