<?php

namespace Phine\Bundles\Core\Snippets\FormParts;

use Phine\Bundles\Core\Logic\Snippet\TemplateSnippet;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Site;
use Phine\Bundles\Core\Modules\Backend\AjaxSelectPage;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Framework\System\IO\Path;
use Phine\Framework\FormElements\Interfaces\IFormElement;
use Phine\Framework\Validation\Interfaces\IValidator;
use Phine\Framework\FormElements\Fields\Input;
use Phine\Bundles\Core\Snippets\FormFields\HiddenInputField;
use Phine\Framework\System\Str;
use Phine\Framework\System\Http\Request;
/**
 * Renders the fields for page url selector
 */
class PageSelector extends TemplateSnippet implements IFormElement
{
    /**
     * A prefix to make the selector unique
     * @var string
     */
    protected $prefix;

    /**
     * The resulting page name
     * @var string
     */
    protected $name = '';
    

    /**
     * The hidden field for the page id
     * @var Input
     */
    protected $pageField;
    
    /**
     * The element's label
     * @var string
     */
    protected $label;

    /**
     * Sets a single site to restrict pages
     * @var Site
     */
    private $site;
    /**
     * List of disabled page ids
     * @var int[]
     */
    private $disabledPageIDs;
    
    /**
     * Creates a new page url selector
     * @param string $prefix The prefix
     * @param string $label The selector label
     * @param Page $page The pre-selected page
     */
    function __construct($prefix, $label = '',  Page $page= null) 
    {
        $this->disabledPageIDs = array();
        $this->prefix = $prefix;
        $this->label = $label;
        $this->site = null;
        $this->pageField = Input::Hidden($prefix . 'Page');
        if ($page && $page->Exists())
        {
            $this->name = $page->GetName();
            $this->pageField->SetValue($page->GetID());
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
        $args = array('pageOnly'=>'1');
        if (count($this->disabledPageIDs) > 0) {
            $args['disabled'] = $this->disabledPageIDs;
        }
        if ($this->site){
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
     * Gets the selected page
     */
    public function GetPage()
    {
        return $this->Value('Page') ? Page::Schema()->ByID($this->Value('Page')) : null;
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
}