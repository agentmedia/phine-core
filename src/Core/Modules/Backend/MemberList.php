<?php

namespace Phine\Bundles\Core\Modules\Backend;

use App\Phine\Database\Access;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use App\Phine\Database\Core\Member;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Access\Backend\Enums\BackendAction;
use Phine\Framework\Database\Objects\TableObject;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Framework\Access\Base\Action;

/**
 * The member list in the backend
 */
class MemberList extends BackendModule
{
    use Traits\TableObjectRemover;
    /**
     *
     * @var Member[]
     */
    protected $members;
    
    /**
     * Initializes the set of members
     */
    protected function Init()
    {
        $sql = Access::SqlBuilder();
        $tbl = Member::Schema()->Table();
        $order = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $this->members = Member::Schema()->Fetch(false, null, $order);
        return parent::Init();
    }
    
    /**
     * The url to the page with the member edit/create form
     * @param Member $member If member is given, form page will be in edit mode, otherwise in create mode
     * @return string Returns the form page url
     */
    protected function FormUrl(Member $member = null)
    {
        $args = array();
        if ($member)
        {
            $args['member'] = $member->GetID();
        }
        return BackendRouter::ModuleUrl(new MemberForm(), $args);
    }
    
    
    function SideNavIndex()
    {
        return 8;
    }
    
    /**
     * True if a new member can be created
     * @return bool
     */
    protected function CanCreate()
    {
        return self::Guard()->Allow(BackendAction::Create(), new Member());
    }
    
    /**
     * True if member can be edited
     * @param Member $member
     * @return bool
     */
    protected function CanEdit(Member $member)
    {
        return self::Guard()->Allow(BackendAction::Edit(), $member) && 
                self::Guard()->Allow(BackendAction::UseIt(), new MemberForm());
    }
    
    /**
     * True if user can delete the member
     * @param Member $member
     * @return bool
     */
    protected function CanDelete(Member $member)
    {
        return self::Guard()->Allow(BackendAction::Delete(), $member);
    }
    
    
    
    /**
     * Gets the member for removal if delete id is posted
     * @return Member
     */
    protected function RemovalObject()
    {
        $id = Request::PostData('delete');
        return $id ? new Member($id) : null;
    }
    
    protected function BeforeRemove(TableObject $deleteObject)
    {
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportMemberAction($deleteObject, Action::Delete());
    }
    /**
     * The link for the back button
     * @return string Returns the url to the overview
     */
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new Overview());
    }
}

