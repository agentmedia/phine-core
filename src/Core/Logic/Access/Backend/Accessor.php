<?php

namespace Phine\Bundles\Core\Logic\Access\Backend;

use Phine\Framework\Access\Interfaces as AccessInterfaces;
use Phine\Database\Core\User;


/**
 * Accessor for Controls in the cms database
 */
class Accessor implements AccessInterfaces\IAccessor
{
    /**
     *
     * @var string
     */
    private static $sessionParam = 'PhineBackendUserID';
    
    /**
     *
     * @var User
     */
    private $user;
    
    /**
     * Creates a new backed accessor
     */
    public function __construct()
    {
        $this->user = null;
    }
    
    
    
    
    /**
     * True if the accessor is not defined
     * @return bool 
     */
    function IsUndefined()
    {
        return $this->user === null;
    }
    
    /**
     * 
     * Verifies access data and saves current user if successful
     * @param data Array containing 'Name' and 'Password' as keys with values
     * @param $dontSave If true, user is not saved in session (logged in)
     * @return bool
     */
    function Verify($data, $dontSave = false)
    {
        if (!isset($data['Password']) || !isset($data['Name']))
            return false;
        
        
        $name = $data['Name'];
        $user = User::Schema()->ByName($name);
        if ($user)
        {
            $password = $data['Password'];
            $pwHash = hash('sha256', $password . $user->GetPasswordSalt());
            if ($pwHash == $user->GetPassword())
            {
                if (!$dontSave)
                {
                    $this->user = $user;
                    $_SESSION[self::$sessionParam] = $this->user->GetID();
                }
                return true;
            }
        }
        return false;
    }
    
    /**
     * 
     *  Undefines this accessor
     */
    function Undefine()
    {
        unset ($_SESSION[self::$sessionParam]);
        $this->user = null;
    }
    
    /**
     * Loads the currently active accessor into this instance
     * @return bool
     */
    function LoadCurrent()
    {
        $this->user = null;
        $userID = null;
        if (isset($_SESSION[self::$sessionParam]))
             $userID = $_SESSION[self::$sessionParam];
        
        if ($userID)
        {
            $user = new User($userID);
            if ($user->Exists())
                $this->user = $user;
        }
        return $this->user !== null;
    }
    
    /**
     * 
     * Gets the curently logged in user
     * @return User
     */
    public function User()
    {
        return $this->user;
    }
    
}

