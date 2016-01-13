<?php
namespace Phine\Bundles\Core\Logic\Util;
use Phine\Framework\FormElements\Fields\Select;
use Phine\Database\Access;
use Phine\Database\Core\Usergroup;
use Phine\Database\Core\Language;
use Phine\Framework\Database\Sql\Condition;

class DBSelectUtil
{
    static function AddUserGroupOptions(Select $field)
    {
        $sql = Access::SqlBuilder();
        $tblGroup = Usergroup::Schema()->Table();
        $orderBy = $sql->OrderList($sql->OrderAsc($tblGroup->Field('Name')));
        $groups = Usergroup::Schema()->Fetch(false, null, $orderBy);
        foreach ($groups as $group)
        {
            $field->AddOption($group->GetID(), $group->GetName());
        }
    }
    
    static function AddLanguageOptions(Select $field, Condition $where = null)
    {
        $sql = Access::SqlBuilder();
        $tblLang = Language::Schema()->Table();
        $orderBy = $sql->OrderList($sql->OrderAsc($tblLang->Field('Name')));
        $langs = Language::Schema()->Fetch(false, $where, $orderBy);
        foreach ($langs as $lang)
        {
            $field->AddOption($lang->GetID(), $lang->GetName() . ' (' . $lang->GetCode() . ')');
        }
    }
            
}

