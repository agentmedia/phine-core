<?php

namespace Phine\Bundles\Core\Logic\Module;
use Phine\Framework\System\Str;

abstract class AjaxBackendForm extends BackendModule
{
    use Traits\Form;
    
    protected function RedirectModal($url)
    {
        $jsUrl = Str::ToJavascript($url, false);
        echo "<script>$('#ajax-modal').load($jsUrl);</script>";
        die();
    }
    
    protected function CloseModal()
    {
        echo "<script>$('#ajax-modal').foundation('reveal', 'close');</script>";
        die();
    }
    
    /**
     * Sets a field value as typical action of a modal form call
     * @param string $field The field specifier; typically starting with '#'
     * @param string $value The value
     */
    protected function SetJSFieldValue($field, $value)
    {
        $jsField = Str::ToJavascript($field, false);
        $jsValue = Str::ToJavascript($value, false);
        echo "<script>$($jsField).val($jsValue);</script>";
    }
    
    /**
     * Sets element html content as a typical action of a modal form call
     * @param string $element The element specifier; typically starting with '#'
     * @param string $html The html string
     */
    protected function SetJSHtml($element, $html)
    {
        $jsElement = Str::ToJavascript($element, false);
        $jsHtml = Str::ToJavascript($html, false);
        echo "<script>$($jsElement).html($jsHtml);</script>";
    }
}

