<?php
namespace Phine\Bundles\Core\Snippets\FormFields;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
/**
 * Renders an input field of type file and its label before
 */
class FileInputField extends TemplateSnippet
{
    /**
     * The file input field
     * @var Input
     */
    protected $field;
    /**
     * The html attributes of the rendered input field
     * @var array
     */
    protected $attribs;
    /**
     * The preview image url
     * @var string
     */
    protected $previewImage;
    /**
     * 
     * @param Input $field The field
     * @param string $previewImage The url of an optional preview image
     */
    function __construct(Input $field, $previewImage = '')
    {
        $this->field = $field;
        $this->attribs = $field->GetHtmlAttributes();
        $this->previewImage = $previewImage;
    }
}
