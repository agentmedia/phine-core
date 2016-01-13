<?php

namespace Phine\Bundles\Core\Modules\Backend;
use Phine\Bundles\Core\Logic\Module\AjaxBackendForm;
use Phine\Database\Core\Page;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Framework\FormElements\Fields\Textarea;
use Phine\Bundles\Core\Logic\Util\ArrayLinesSerializer;


/**
 * Ajax form for params and fragment of a previously selected page
 */
class AjaxPageParams extends AjaxBackendForm
{
    /**
     * The page for which to give the 
     * @var Page
     */
    protected $page;
    
    /**
     * The prefix
     * @var string
     */
    protected $prefix;
    
    /**
     * Associative parameter array as already set
     * @var 
     */
    private $params = array();
    /**
     * Obligatory parameter names
     * @var array
     */
    protected $oblParams = array();
    /**
     * The serializer for parameter representation
     * @var ArrayLinesSerializer
     */
    private $serializer;
    protected function Init()
    {
        $this->prefix = Request::GetData('prefix');
        $this->page = Page::Schema()->ByID(Request::GetData('page'));
        if (!$this->page)
        {
            throw new \Exception('Required parameter page invalid or missing');
        }
        $this->serializer = new ArrayLinesSerializer();
        $this->oblParams = FrontendRouter::GatherParams($this->page->GetUrl());
        $this->params = $this->serializer->LinesToArray(Request::GetData('params'));
        $this->AddObligatoryFields();
        $this->AddOptionalParamsField();
        $this->AddFragmentField();
        $this->AddSubmit();
        return parent::Init();
    }
    
    private function AddObligatoryFields()
    {
        foreach ($this->oblParams as $name)
        {
            $field = Input::Text($name, isset($this->params[$name]) ? $this->params[$name] : '');
            $this->AddField($field, false, $name);
            $this->SetRequired($name, 'Core.AjaxPageParams.Param.');
        }
    }
    
    /**
     * Adds the optional parameters textarea
     */
    private function AddOptionalParamsField()
    {
        $name = 'OptionalParameters';
        $field = new Textarea($name, $this->OptionalParamsText());
        $this->AddField($field);
        $this->SetTransAttribute($name, 'placeholder');
    }
    /**
     * Adds the fragment field
     */
    private function AddFragmentField()
    {
        $name = 'Fragment';
        $field = Input::Text($name, Request::GetData($this->prefix . 'Fragment'));
        $this->AddField($field);
        $this->SetTransAttribute($name, 'placeholder');
    }
    /**
     * The field value of the optional paramaters
     * @return string Returns the optional parameters as multiline text
     */
    private function OptionalParamsText()
    {
        $params = $this->params;
        foreach ($this->oblParams as $name)
        {
            unset($params[$name]);
        }
        return $this->serializer->ArrayToLines($params);
    }
    
    /**
     * Saves the page selection
     */
    protected function OnSuccess()
    {
        $allParams = array();
        foreach ($this->oblParams as $param)
        {
            $allParams[$param] = $this->Value($param);
        }
        $optParams = $this->serializer->LinesToArray($this->Value('OptionalParameters'));
        foreach ($optParams as $name=>$value)
        {
            if (!isset($allParams[$name]))
            {
                $allParams[$name] = $value;
            }
        }
        $this->SetJSFieldValue('#' . $this->prefix . 'Params', $this->serializer->ArrayToLines($allParams));
        $this->SetJSFieldValue('#' . $this->prefix . 'Page', $this->page->GetID());
        $this->SetJSFieldValue('#' . $this->prefix . 'Fragment', $this->Value('Fragment'));
        $this->SetJSHtml('#' . $this->prefix . 'Url', FrontendRouter::PageUrl($this->page, $allParams, $this->Value('Fragment')));
        $this->CloseModal();
    }

}
