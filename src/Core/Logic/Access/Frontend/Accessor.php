<?php

namespace Phine\Bundles\Core\Logic\Access\Frontend;

use Phine\Framework\Access\Interfaces as AccessInterfaces;
use Phine\Database\Core\Member;


/**
 * Accessor for frontend pages and contents of the phine cms
 */
class Accessor implements AccessInterfaces\IAccessor
{
    /**
     *
     * @var string
     */
    private static $sessionParam = 'PhineFrontendMemberID';
    
    /**
     *
     * @var Member
     */
    private $member = null;
    
    /**
     * Creates a new frontend accessor
     */
    public function __construct()
    {
    }
    
    
    
    
    /**
     * True if the accessor is not defined
     * @return bool 
     */
    function IsUndefined()
    {
        return $this->member === null;
    }
    
    /**
     * 
     * Verifies access data and saves current member if successful
     * @param data Array containing 'Name' and 'Password' as keys with values
     * @param $dontSave If true, member is not saved in session (logged in)
     * @return bool Returns true if login data is correct
     */
    function Verify($data, $dontSave = false)
    {
        if (!isset($data['Password']) || !isset($data['Name']))
        {
            return false;
        }
        $name = $data['Name'];
        $member = Member::Schema()->ByName($name);
        if (!$member)
        {
            $member = Member::Schema()->ByEMail($name);
        }
        if (!$member || !$member->GetActive() || !$member->GetConfirmed())
        {
            return false;
        }
        return $this->VerifyMember($member, $data['Password'], $dontSave);
    }
    
    private function VerifyMember(Member $member, $password, $dontSave = false)
    {
        $pwHash = hash('sha256', $password . $member->GetPasswordSalt());
        if ($pwHash == $member->GetPassword())
        {
            if (!$dontSave)
            {
                $this->member = $member;
                $_SESSION[self::$sessionParam] = $this->member->GetID();
            }
            return true;
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
        $this->member = null;
    }
    
    /**
     * Loads the currently active accessor into this instance
     * @return bool
     */
    function LoadCurrent()
    {
        $this->member = null;
        $memberID = null;
        if (isset($_SESSION[self::$sessionParam]))
        {
             $memberID = $_SESSION[self::$sessionParam];
        }
        if ($memberID)
        {
            $this->member = Member::Schema()->ByID($memberID);
        }
        return $this->member !== null;
    }
    
    /**
     * 
     * Gets the curently logged in member
     * @return Member
     */
    public function Member()
    {
        return $this->member;
    }
    
}

