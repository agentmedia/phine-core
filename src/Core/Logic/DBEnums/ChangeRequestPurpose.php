<?php
namespace Phine\Bundles\Core\Logic\DBEnums;
use Phine\Framework\System\Enum;

/**
 * Purposes for member data change requests
 */
class ChangeRequestPurpose extends Enum
{
    
    /**
     * Marks that the member wants to change the email
     * @return ChangeRequestPurpose
     */
    static function NewEMail()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Marks that the member wants to change the password
     * @return ChangeRequestPurpose
     */
    static function NewPassword()
    {
        return new self(__FUNCTION__);
    }
}
