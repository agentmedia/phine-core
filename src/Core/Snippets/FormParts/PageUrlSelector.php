<?php

namespace Phine\Bundles\Core\Snippets\FormParts;

use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use App\Phine\Database\Core\PageUrl;
use App\Phine\Database\Core\PageUrlParameter;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Site;
use Phine\Bundles\Core\Logic\Tree\PageParamListProvider;
use Phine\Bundles\Core\Logic\Util\ArrayLinesSerializer;
use Phine\Bundles\Core\Modules\Backend\AjaxSelectPage;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\System\IO\Path;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
use Phine\Framework\FormElements\Interfaces\IFormElement;
use Phine\Framework\Validation\Interfaces\IValidator;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Bundles\Core\Snippets\FormFields\HiddenInputField;
use App\Phine\Database\Access;
use Phine\Framework\System\Str;
use Phine\Framework\System\Http\Request;

/**
 * Renders the fields for page url selector
 */
class PageUrlSelector extends TemplateSnippet implements IFormElement
{

    /**
     * A prefix to make the selector unique
     * @var string
     */
    protected $prefix;

    /**
     * The resulting web address
     * @var string
     */
    protected $url = '';

    /**
     * The hidden field for the page id
     * @var Input
     */
    protected $pageField;

    /**
     * The hidden field for the fragment
     * @var Input
     */
    protected $fragmentField;

    /**
     * The hidden field for the parameters
     * @var Input
     */
    protected $paramsField;

    /**
     * The element's label
     * @var string
     */
    protected $label;

    /**
     * The serializer for the parameters
     * @var ArrayLinesSerializer
     */
    protected $serializer;

    /**
     * List of disabled page ids
     * @var int[]
     */
    private $disabledPageIDs;
    
    /**
     * An optional site the pages are restricted to
     * @var Site
     */
    private $site;
    /**
     * Creates a new page url selector
     * @param string $prefix The prefix
     * @param string $label The selector label
     * @param PageUrl $pageUrl The pre-selected page url
     */
    function __construct($prefix, $label = '', PageUrl $pageUrl = null)
    {
        $this->disabledPageIDs = array();
        $this->prefix = $prefix;
        $this->label = $label;
        $this->site = null;
        $this->pageField = Input::Hidden($prefix . 'Page');
        $this->paramsField = Input::Hidden($prefix . 'Params');
        $this->fragmentField = Input::Hidden($prefix . 'Fragment');
        $this->serializer = new ArrayLinesSerializer();
        if ($pageUrl && $pageUrl->Exists()) {
            $this->url = FrontendRouter::Url($pageUrl);
            $this->pageField->SetValue($pageUrl->GetPage()->GetID());
            $list = new PageParamListProvider($pageUrl);
            $this->paramsField->SetValue($this->serializer->ArrayToLines($list->ToArray()));
            $this->fragmentField->SetValue($pageUrl->GetFragment());
        }
    }
    
    /**
     * Sets a site to restrict pages
     * @param Site $site The site for the pages
     */
    public function SetSite(Site $site)
    {
        $this->site = $site;
    }

    public function DisablePage(Page $page)
    {
        $this->disabledPageIDs[] = $page->GetID();
    }

    protected function OpenUrl()
    {
        $args = array('pageOnly' => '0');
        if (count($this->disabledPageIDs) > 0) {
            $args['disabled'] = $this->disabledPageIDs;
        }
        if ($this->site) {
            $args['site'] = $this->site->GetID();
        }
        return BackendRouter::AjaxUrl(new AjaxSelectPage(), $args);
    }

    /**
     * Renders the hidden page field
     * @return string Returns the renderered field
     */
    protected function RenderPageField()
    {
        $hiddenField = new HiddenInputField($this->pageField);
        return $hiddenField->Render();
    }

    /**
     * Renders the hidden params field
     * @return string Returns the renderered field
     */
    protected function RenderParamsField()
    {
        $hiddenField = new HiddenInputField($this->paramsField);
        return $hiddenField->Render();
    }

    /**
     * Renders the hidden fragment field
     * @return string Returns the renderered field
     */
    function RenderFragmentField()
    {
        $hiddenField = new HiddenInputField($this->fragmentField);
        return $hiddenField->Render();
    }

    /**
     * Renderst necessary javascript
     * @return string Returns javascript for html output
     */
    function RenderScript()
    {
        $templateFile = Path::RemoveExtension($this->TemplateFile());
        $scriptFile = Path::AddExtension($templateFile, 'Script');
        ob_start();
        require Path::AddExtension($scriptFile, 'phtml');
        return ob_get_clean();
    }

    /**
     * Sets the field to not required
     * @param string $errorLabelPrefix
     */
    public function SetRequired($errorLabelPrefix = '')
    {
        $this->pageField->SetRequired($errorLabelPrefix);
    }

    /**
     * Marks the field as not required
     */
    public function SetNotRequired()
    {
        $this->pageField->SetNotRequired();
    }

    public function AddValidator(IValidator $validator)
    {
        $this->pageField->AddValidator($validator);
    }

    /**
     * Check the selector
     * @param array $data 
     */
    public function Check($data)
    {
        $value = $data[$this->prefix . 'Page'];
        return $this->pageField->Check($value);
    }

    /**
     * Gets a boolean for failed check
     * @return bool Returns true if a prior executed check failed
     */
    public function CheckFailed()
    {
        return $this->pageField->CheckFailed();
    }

    /**
     * Clears all validators from the selector
     */
    public function ClearValidators()
    {
        $this->pageField->ClearValidators();
    }

    /**
     * Gets all validators
     * @return IValidator[]
     */
    public function GetValidators()
    {
        return $this->pageField->GetValidators();
    }

    /**
     * Saves the page url and returns it
     * @param PageUrl $pageUrl The page url
     * @return PageUrl Returns the page url with properties attached
     */
    public function Save(PageUrl $pageUrl = null)
    {
        $exists = $pageUrl && $pageUrl->Exists();
        $page = $this->Value('Page') ? Page::Schema()->ByID($this->Value('Page')) : null;
        if (!$page) {
            if ($exists) {
                $pageUrl->Delete();
            }
            return null;
        }
        if (!$exists) {
            $pageUrl = new PageUrl();
        }
        $pageUrl->SetPage($page);
        $pageUrl->SetFragment($this->Value('Fragment'));
        $pageUrl->Save();
        $this->SaveParams($pageUrl);
        return $pageUrl;
    }

    /**
     * Saves the page paramters after the page url is saved
     * @param PageUrl $pageUrl The page url
     */
    private function SaveParams(PageUrl $pageUrl)
    {
        $this->ClearParams($pageUrl);
        $params = $this->serializer->LinesToArray($this->Value('Params'));
        $prev = null;
        foreach ($params as $name => $value) {
            $param = new PageUrlParameter();
            $param->SetPageUrl($pageUrl);
            $param->SetPrevious($prev);
            $param->SetName($name);
            $param->SetValue($value);
            $param->Save();
            $prev = $param;
        }
    }

    /**
     * Gets the value by pure name
     * @param string $name The name without prefix
     * @return string Returns the value
     */
    private function Value($name)
    {
        $value = Request::PostData($this->prefix . $name);
        return Str::Trim($value);
    }

    /**
     * Clears the parameters of the page url
     * @param PageUrl $pageUrl The page url
     */
    private function ClearParams(PageUrl $pageUrl)
    {
        $sql = Access::SqlBuilder();
        $tblParams = PageUrlParameter::Schema()->Table();
        $where = $sql->Equals($tblParams->Field('PageUrl'), $sql->Value($pageUrl->GetID()));
        PageUrlParameter::Schema()->Delete($where);
    }

}
