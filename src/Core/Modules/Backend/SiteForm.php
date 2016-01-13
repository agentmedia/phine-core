<?php
namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\System\Http\Request;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Database\Core\Site;
use Phine\Framework\Validation\DatabaseCount;
use Phine\Framework\Validation\PhpFilter;
use Phine\Framework\Validation\Integer;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Snippets\BackendRights\SiteRights;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Database\Core\Usergroup;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Database\Core\Language;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

class SiteForm extends BackendForm
{
    
    /**
     * The site currently edited
     * @var Site
     */
    private $site;
    
    /**
     * The site rights
     * @var SiteRights
     */
    protected  $siteRights;
    protected function Init()
    {
        $this->site = new Site(Request::GetData('site'));
        $this->siteRights = new SiteRights($this->site->GetUserGroupRights());
        $this->AddNameField();
        $this->AddUrlField();
        $this->AddLanguageField();
        $this->AddSubmit();
        $this->AddUserGroupField();
        $this->AddSitemapActiveField();
        $this->AddSitemapCacheLifetimeField();
        
        return parent::Init();
    }
    
    private function AddLanguageField()
    {
        $name = 'Language';
        $field = new Select($name, $this->site->Exists() ? $this->site->GetLanguage()->GetID() : '');
        $field->AddOption('', Trans('Core.PleaseSelect'));
        DBSelectUtil::AddLanguageOptions($field);
        $this->AddField($field);
        $this->SetRequired($name);
    }
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->site->GetName()));
        $this->SetRequired($name);   
        $this->AddValidator($name, DatabaseCount::UniqueField($this->site, 'Name'));
    }
    
    /**
     * Adds the url field to the form
     */
    private function AddUrlField()
    {
        $name = 'Url';
        $this->AddField(Input::Text($name, $this->site->GetUrl()));
        $this->SetRequired($name);
        $this->AddValidator($name, PhpFilter::Url());
    }
    
    /**
     * Adds the sitemap active checkbox
     */
    private function AddSitemapActiveField()
    {
        $name = 'SitemapActive';
        $field = new Checkbox($name);
        if (!$this->site->Exists() || $this->site->GetSitemapActive())
        {
            $field->SetChecked();
        }
        $this->AddField($field);
    }
    
    private function AddSitemapCacheLifetimeField()
    {
        $name = 'SitemapCacheLifetime';
        $value = $this->site->Exists() ? $this->site->GetSitemapCacheLifetime() : 24 * 60 * 60;
        $field = Input::Text($name, $value);
        $this->AddField($field);
        $this->AddValidator($name, Integer::PositiveOrNull());
    }
    
    /**
     * Saves the site
     */
    protected function OnSuccess()
    {
        $action = Action::Update();
        if (!$this->site->Exists())
        {
            $action = Action::Create();
            $this->site->SetUser(self::Guard()->GetUser());
        }
        $this->site->SetName($this->Value('Name'));
        $this->site->SetUrl($this->Value('Url'));
        $this->site->SetLanguage(Language::Schema()->ByID($this->Value('Language')));
        $this->site->SetSitemapActive((bool)$this->Value('SitemapActive'));
        $this->site->SetSitemapCacheLifetime((int)$this->Value('SitemapCacheLifetime'));
        $this->site->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportSiteAction($this->site, $action);
        if ($this->CanAssignGroup())
        {
            $this->SaveRights();
        }
        Response::Redirect(BackendRouter::ModuleUrl(new SiteList()));
    }
    
     /**
     * True if user group can be assigned
     * @return bool
     */
    protected function CanAssignGroup()      
    {
        return self::Guard()->Allow(BackendAction::AssignGroups(), $this->site);
    }
    
    /**
     * Adds the user group field
     */
    private function AddUserGroupField()
    {
        $name = 'UserGroup';
        $field = new Select($name, '');
        
        $field->AddOption('', Trans('Core.SiteForm.NoGroup'));
        if ($this->site->Exists() && $this->site->GetUserGroup())
        {
            $field->SetValue($this->site->GetUserGroup()->GetID());
        }
        DBSelectUtil::AddUserGroupOptions($field);
        $this->AddField($field);
    }
    
    private function SaveRights()
    {
        $groupID = $this->Value('UserGroup');
        $userGroup = Usergroup::Schema()->ByID($groupID);
        $this->site->SetUserGroup($userGroup);
        if (!$userGroup)
        {
            $oldRights = $this->site->GetUserGroupRights();
            if ($oldRights)
            {
                $oldRights->GetPageRights()->GetContentRights()->Delete();
            }
            $this->site->SetUserGroupRights(null);
        }
        else
        {
            $this->siteRights->Save();
            $this->site->SetUserGroupRights($this->siteRights->Rights());
        }
        $this->site->Save();
    }
    
    /**
     * The link for the back button
     * @return string Returns the url to the site list
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new SiteList());
    }
}

