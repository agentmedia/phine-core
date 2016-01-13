<?php

namespace Phine\Bundles\Core\Modules\Backend;


use Phine\Database\Core\Settings;
use Phine\Bundles\Core\Logic\Config\SettingsProxy;
use Phine\Bundles\Core\Logic\Config\Enums\SmtpSecurity;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Framework\Validation\PhpFilter;
use Phine\Framework\Validation\Integer;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\System\Http\Response;
use Phine\Bundles\Core\Modules\Backend\Overview;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;

class SettingsForm extends BackendForm
{
    /**
     * The unique core settings object
     * @var Settings
     */
    private $settings;
    protected function Init()
    {
        $this->settings = SettingsProxy::Singleton()->Settings();
        $this->AddLogLifetimeField();
        
        $this->AddMailFromEMailField();
        $this->AddMailFromNameField();
        $this->AddSmtpHostField();
        $this->AddSmtpPortField();
        $this->AddSmtpUserField();
        $this->AddSmtpPasswordField();
        $this->AddSmtpSecurityField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    /**
     * Adds the log lifetime field
     */
    private function AddLogLifetimeField()
    {
        $name = 'LogLifetime';
        $field = Input::Text($name, $this->settings->GetLogLifetime());
        $this->AddField($field);
        $this->AddValidator($name, new Integer(0, 730));
        $this->SetRequired($name);
    }
    
    /**
     * Adds the smtp mail from e-mail field
     */
    private function AddMailFromEMailField()
    {
        $name = 'MailFromEMail';
        $field = Input::Text($name, $this->settings->GetMailFromEMail());
        $this->AddField($field);
        $this->AddValidator($name, PhpFilter::EMail());
    }
    
    /**
     * Adds the mail from name field
     */
    private function AddMailFromNameField()
    {
        $name = 'MailFromName';
        $field = Input::Text($name, $this->settings->GetMailFromName());
        $this->AddField($field);
    }
    
    /**
     * Adds the smtp host field
     */
    private function AddSmtpHostField()
    {
        $name = 'SmtpHost';
        $field = Input::Text($name, $this->settings->GetSmtpHost());
        $this->AddField($field);
    }
    
    /**
     * Adds the smtp port field
     */
    private function AddSmtpPortField()
    {
        $name = 'SmtpPort';
        $field = Input::Text($name, $this->settings->GetSmtpPort());
        $this->AddField($field);
        $this->AddValidator($name, new Integer(1, 65535));
    }
    /**
     * Adds the smtp user field
     */
    private function AddSmtpUserField()
    {
        $name = 'SmtpUser';
        $field = Input::Text($name, $this->settings->GetSmtpUser());
        $this->AddField($field);
    }
    
    /**
     * Adds the smtp password field
     */
    private function AddSmtpPasswordField()
    {
        $name = 'SmtpPassword';
        $field = Input::Text($name, $this->settings->GetSmtpPassword());
        $this->AddField($field);
    }
    
    /**
     * Adds the smtp security field
     */
    private function AddSmtpSecurityField()
    {
        $name = 'SmtpSecurity';
        $field = new Select($name, $this->settings->GetSmtpSecurity());
        $values = SmtpSecurity::AllowedValues();
        foreach ($values as $value)
        {
            $field->AddOption($value, Trans("Core.SettingsForm.SmtpSecurity.$value"));
        }
        $this->AddField($field);
    }

    
    /**
     * Saves the settings
     */
    protected function OnSuccess()
    {
        $this->settings->SetLogLifetime((int)$this->Value('LogLifetime'));
        $this->settings->SetMailFromEMail($this->Value('MailFromEMail'));
        $this->settings->SetMailFromName($this->Value('MailFromName'));
        $this->settings->SetSmtpHost($this->Value('SmtpHost'));
        $this->settings->SetSmtpPort($this->Value('SmtpPort'));
        $this->settings->SetSmtpUser($this->Value('SmtpUser'));
        $this->settings->SetSmtpPassword($this->Value('SmtpPassword'));
        $this->settings->SetSmtpSecurity($this->Value('SmtpSecurity'));
        Response::Redirect($this->BackLink());
    }
    
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new Overview());
    }

}