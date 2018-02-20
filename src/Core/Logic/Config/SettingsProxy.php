<?php

namespace Phine\Bundles\Core\Logic\Config;
use App\Phine\Database\Core\Settings;
use App\Phine\Database\Core\User;
use App\Phine\Database\Access;

/**
 * Proxy for the settings; use this instead of settings directly to assure there is exactly one settings database entry
 */
class SettingsProxy
{
    /**
     * The settings
     * @var Settings
     */
    private $settings;
    /**
     *
     * @var SettingsProxy
     */
    private static $singleton;
    
    /**
     * The proxy as singleton objext
     * @return SettingsProxy Returns the proxy singleton
     */
    static function Singleton()
    {
        if (!self::$singleton)
        {
            self::$singleton = new self();
        }
        return self::$singleton;
    }
    
    /**
     * Returns the settings object representing the unique database entry
     * @return Settings
     */
    function Settings()
    {
        return $this->settings;
    }
    
    /**
     * Creates the proxy and a setting with default values, if nothing exists yet
     */
    private function __construct()
    {
        $this->settings = Settings::Schema()->First();
        if (!$this->settings)
        {
            $this->CreateSettings();
        }
    }
    /**
     * Creates the settings initally
     */
    private function CreateSettings()
    {
        $this->settings = new Settings();
        $this->settings->SetLogLifetime(90);
        $this->settings->SetChangeRequestLifetime(30);
        $this->settings->SetSmtpSecurity((string)Enums\SmtpSecurity::None());
        $admin = $this->FirstAdmin();
        if ($admin)
        {
            $this->settings->SetMailFromEMail($admin->GetEMail());
            if ($admin->GetFirstName() &&  $admin->GetLastName())
            {
                $this->settings->SetMailFromName($admin->GetFirstName() . ' ' . $admin->GetLastName());
            }
        }
        $this->settings->Save();
    }
    
    /**
     * Gets the first user that is an admin
     * @return User The admin user, if present
     */
    private function FirstAdmin()
    {
        $sql = Access::SqlBuilder();
        $tblUser = User::Schema()->Table();
        $where = $sql->Equals($tblUser->Field('IsAdmin'), $sql->Value(true));
        $orderBy = $sql->OrderList($sql->OrderAsc($tblUser->Field('ID')));
        return User::Schema()->First($where, $orderBy);
    }
}

