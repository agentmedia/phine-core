<?php
namespace Phine\Bundles\Core\Logic\Module;
use Phine\Bundles\Core\Logic\Module\Traits;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\FormElements\Fields\Custom;
use Phine\Framework\FormElements\RichTextEditor\CKEditorRenderer;
abstract class BackendForm extends BackendModule
{
   use Traits\Form;
   /**
    * Adds a field with the default rich text editor
    * @param string $name The field name
    * @param string $value The field value
    */
   protected function AddRichTextField($name, $value = '')
   {
        $renderer = new CKEditorRenderer(PathUtil::BackendRTEPath(), PathUtil::BackendRTEUrl(),
            PathUtil::UploadPath(), PathUtil::UploadUrl(), self::Guard());
        $field = new Custom($renderer);
        $field->SetName($name);
        $field->SetValue($value);
        $this->AddField($field);
   }
}

