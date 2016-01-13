<?php

namespace Phine\Bundles\Core\Logic\Module;
use Phine\Framework\System\String;

abstract class AjaxBackendForm extends BackendModule
{
    use Traits\Form;
    
    protected function RedirectModal($url)
    {
        $jsUrl = String::ToJavascript($url, false);
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
        $jsField = String::ToJavascript($field, false);
        $jsValue = String::ToJavascript($value, false);
        echo "<script>$($jsField).val($jsValue);</script>";
    }
    
    /**
     * Sets element html content as a typical action of a modal form call
     * @param string $element The element specifier; typically starting with '#'
     * @param string $html The html string
     */
    protected function SetJSHtml($element, $html)
    {
        $jsElement = String::ToJavascript($element, false);
        $jsHtml = String::ToJavascript($html, false);
        echo "<script>$($jsElement).html($jsHtml);</script>";
    }
}

