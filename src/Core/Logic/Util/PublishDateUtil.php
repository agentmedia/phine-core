<?php
namespace Phine\Bundles\Core\Logic\Util;
use Phine\Framework\System\Date;

/**
 * Utility for datewise published items
 */
class PublishDateUtil
{
    /**
     * 
     * @param boolean $publish A flag to activate/deactivate publishing generally
     * @param Date $from The from date; null denoting no restriction in start date
     * @param Date $to The to date; null denoting no restriction in end date
     * @return boolean Returns true if publish is true and now is between from and to date
     */
    public static function IsPublishedNow($publish, Date $from = null, Date $to = null)
    {
        if (!$publish)
        {
            return false;
        }
        $now = Date::Now();
        if ($from && $from->IsAfter($now))
        {
            return false;
        }
        if ($to && $to->IsBefore($now))
        {
            return false;
        }
        return true;
    }
}

