<?php

namespace Phine\Bundles\Core\Logic\Util;
use App\Phine\Database\Core\Member;
use App\Phine\Database\Core\Membergroup;
use App\Phine\Database\Core\MemberMembergroup;
use App\Phine\Database\Core\PageMembergroup;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\ContentMembergroup;
use App\Phine\Database\Core\Content;
use Phine\Framework\FormElements\Fields\CheckList;
use Phine\Framework\Database\Sql\JoinType;
use App\Phine\Database\Access;

/**
 * Provides methods to retrieve member group infos and form fields
 */
class MembergroupUtil
{
    
    /**
     * Creates a check list field for groups of a member
     * @param string $name The field name
     * @param Member $member The member
     * @return CheckList Returns the check list 
     */
    static function MemberCheckList($name, Member $member)
    {    
        $field = new CheckList($name);
        self::AddMembergroupOptions($field);
        $groupIDs = Membergroup::GetKeyList(self::MemberMembergroups($member));
        $field->SetValue($groupIDs);
        return $field;
    }
    
    /**
     * Creates a check list field for groups of a page
     * @param string $name The field name
     * @param Page $page The page
     * @return CheckList Returns the check list
     */
    static function PageCheckList($name, Page $page)
    {    
        $field = new CheckList($name);
        self::AddMembergroupOptions($field);
        $groupIDs = Membergroup::GetKeyList(self::PageMembergroups($page));
        $field->SetValue($groupIDs);
        return $field;
    }
    
    /**
     * Creates a check list field for groups of a content
     * @param string $name The field name
     * @param Content $content The content
     * @return CheckList Returns the check list
     */
    static function ContentCheckList($name, Content $content)
    {    
        $field = new CheckList($name);
        self::AddMembergroupOptions($field);
        $groupIDs = Membergroup::GetKeyList(self::ContentMembergroups($content));
        $field->SetValue($groupIDs);
        return $field;
    }
    
    
    /**
     * Gets the member's member groups
     * @param Member $member
     * @return Membergroup[] Returns the member groups assigned to the user
     */
    static function MemberMembergroups(Member $member)
    {
        if (!$member->Exists())
        {
            return array();
        }
        $sql = Access::SqlBuilder();
        $tblMmg = MemberMembergroup::Schema()->Table();
        $tblMg = Membergroup::Schema()->Table();
        $join = $sql->Join($tblMmg);
        $joinCondition = $sql->Equals($tblMmg->Field('MemberGroup'), $tblMg->Field('ID'));
        $where = $sql->Equals($tblMmg->Field('Member'), $sql->Value($member->GetID()));
        $orderBy = $sql->OrderList($sql->OrderAsc($tblMg->Field('Name')));
        
        return Membergroup::Schema()->Fetch(false, $where, $orderBy, null, 0, null, $join, JoinType::Inner(), $joinCondition);
    }
    
    /**
     * Gets the page's member groups
     * @param Page $page
     * @return Membergroup[] Returns the member groups assigned to the page
     */
    static function PageMembergroups(Page $page)
    {
        if (!$page->Exists())
        {
            return array();
        }
        $sql = Access::SqlBuilder();
        $tblPmg = PageMembergroup::Schema()->Table();
        $tblMg = Membergroup::Schema()->Table();
        $join = $sql->Join($tblPmg);
        $joinCondition = $sql->Equals($tblPmg->Field('MemberGroup'), $tblMg->Field('ID'));
        $where = $sql->Equals($tblPmg->Field('Page'), $sql->Value($page->GetID()));
        $orderBy = $sql->OrderList($sql->OrderAsc($tblMg->Field('Name')));
        
        return Membergroup::Schema()->Fetch(false, $where, $orderBy, null, 0, null, $join, JoinType::Inner(), $joinCondition);
    }
    
    /**
     * Gets the content's member groups
     * @param Content $content
     * @return Membergroup[] Returns the member groups assigned to the content
     */
    static function ContentMembergroups(Content $content)
    {
        if (!$content->Exists())
        {
            return array();
        }
        $sql = Access::SqlBuilder();
        $tblCmg = ContentMembergroup::Schema()->Table();
        $tblMg = Membergroup::Schema()->Table();
        $join = $sql->Join($tblCmg);
        $joinCondition = $sql->Equals($tblCmg->Field('MemberGroup'), $tblMg->Field('ID'));
        $where = $sql->Equals($tblCmg->Field('Content'), $sql->Value($content->GetID()));
        $orderBy = $sql->OrderList($sql->OrderAsc($tblMg->Field('Name')));
        
        return Membergroup::Schema()->Fetch(false, $where, $orderBy, null, 0, null, $join, JoinType::Inner(), $joinCondition);
    }
    
    /**
     * Adds the member group options to the checklist
     * @param CheckList $field
     */
    private static function AddMembergroupOptions(CheckList $field)
    {
        $sql = Access::SqlBuilder();
        $tbl = Membergroup::Schema()->Table();
        $orderBy = $sql->OrderList($sql->OrderAsc($tbl->Field('Name')));
        $groups = Membergroup::Schema()->Fetch(false, null, $orderBy);
        foreach ($groups as $group)
        {
            $field->AddOption($group->GetID(), $group->GetName());
        }
    }
    
    
}

