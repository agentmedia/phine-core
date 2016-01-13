<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\Validation\DatabaseCount;

use Phine\Database\Core\User;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Framework\FormElements\Fields\Checkbox;
use Phine\Framework\Validation\CompareCheck;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Validation\PhpFilter;
use Phine\Framework\Validation\StringLength;
use Phine\Framework\System\String;
use Phine\Bundles\Core\Logic\Util\DBSelectUtil;
use Phine\Database\Core\Language;
use Phine\Bundles\Core\Snippets\FormParts\FieldColumnizer;
use Phine\Database\Access;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The user form
 */
class UserForm extends BackendForm
{
    
    /**
     * The user currently edited
     * @var User
     */
    private $user;
    
    /**
     *
     * @var FieldColumnizer
     */
    protected $groupFields;
    
    /**
     * Initializes the form
     * @return boolean
     */
    protected function Init()
    {        
        $this->user = new User(Request::GetData('user'));
        
        $this->AddNameField();
        $this->AddEmailField();
        $this->AddPasswordField();
        $this->AddPasswordRepeatField();
        $this->AddIsAdminField();
        $this->AddLanguageField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    /**
     * Adds name field to the form
     */
    private function AddNameField()
    {
        $name = 'Name';
        $this->AddField(Input::Text($name, $this->user->GetName()));
        $this->SetRequired($name);
        $this->AddValidator($name, DatabaseCount::UniqueField($this->user, 'Name'));
    }
    
    private function AddLanguageField()
    {
        $name = 'Language';
        $lang = $this->user->GetLanguage();
        $field = new Select($name, $lang ? $lang->GetID() : '');
        
        $field->AddOption('', Trans('Core.PleaseSelect'));
        $sql = Access::SqlBuilder();
        $tbl = Language::Schema()->Table();
        $where = $sql->Equals($tbl->Field('IsBackendTranslated'), $sql->Value(true));
        DBSelectUtil::AddLanguageOptions($field, $where);
        $this->AddField($field);
        $this->SetRequired($name);
    }
    
    
    
    /**
     * Adds name field to the form
     */
    private function AddEMailField()
    {
        $name = 'EMail';
        $this->AddField(Input::Text($name, $this->user->GetEMail()));
        $this->SetRequired($name);
        $this->AddValidator($name, PhpFilter::EMail());
    }
    
    /**
     * Adds the password field
     */
    private function AddPasswordField()
    {
        $name = 'Password';
        
        $this->AddField(Input::Password($name));
        //Password needs contain lower case letter, upper case letter, and a digit
        //$validator = new RegExp('/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]))/');
        //$this->AddValidator($name, $validator);
        $this->AddValidator($name, new StringLength(6, 20));
        if (Request::PostData('PasswordRepeat') || !$this->user->Exists())
        {
            $this->SetRequired($name);
        }
    }
    
    
    /**
     * Adds the locked field
     */
    private function AddPasswordRepeatField()
    {
        $name = 'PasswordRepeat';
        $this->AddField(Input::Password($name));
        if (Request::PostData('Password'))
        {
            $this->SetRequired($name);
            $this->AddValidator($name, CompareCheck::Equals($this->Value('Password')));
        }
    }
    
    private function AddIsAdminField()
    {
        $name = 'IsAdmin';
        
        $field = new Checkbox($name, '1', $this->user->GetIsAdmin());
        if (!$this->CanChangeIsAdmin())
        {
            $field->SetHtmlAttribute('disabled', 'disabled');
        }
        $this->AddField($field);
    }
    
    
    protected function CanChangeIsAdmin()
    {
        return self::Guard()->Allow(BackendAction::ChangeIsAdmin(), $this->user);
    }
    
    
    
    /**
     * Saves the area
     */
    protected function OnSuccess()
    {
        $action = $this->user->Exists() ? Action::Update() : Action::Create();
        $this->user->SetName($this->Value('Name'));
        $this->user->SetEMail($this->Value('EMail'));
        $this->user->SetLanguage(new Language($this->Value('Language')));
        if ($this->CanChangeIsAdmin())
        {
            $this->user->SetIsAdmin((bool)$this->Value('IsAdmin'));
        }
        $this->SavePassword();
        $this->user->Save();
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportUserAction($this->user, $action);
        Response::Redirect(BackendRouter::ModuleUrl(new UserList()));
    }
    
    private function SavePassword()
    {
        $password = $this->Value('Password');
        if ($password)
        {
            $salt = String::Start(md5(uniqid(microtime())), 8);
            $pwHash = hash('sha256', $password . $salt);
            $this->user->SetPassword($pwHash);
            $this->user->SetPasswordSalt($salt);
        }
    }
}