<?php

namespace Phine\Bundles\Core\Logic\Config\Enums;
use Phine\Framework\System\Enum;

/**
 * The available types of smtp security options
 */
class SmtpSecurity extends Enum
{
    /**
     * Represents no smtp security on password or data transfer
     * @return SmtpSecurity Returns smtp without any security mode
     */
    static function None()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents (the old) ssl security mode
     * @return SmtpSecurity Returns the ssl smtp security mode
     */
    static function Ssl()
    {
        return new self(__FUNCTION__);
    }
    
    /**
     * Represents tls security mode
     * @return SmtpSecurity Returns the tls smtp security mode
     */
    static function Tls()
    {
        return new self(__FUNCTION__);
    }
}

