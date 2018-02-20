<?php

namespace Phine\Bundles\Core\Snippets\BackendRights;
use App\Phine\Database\Core\BackendPageRights;
use Phine\Bundles\Core\Snippets\BackendRights\Base\RightsCheckboxes;

/**
 * Displays form elements for page access rights in the backend
 */
class PageRights extends RightsCheckboxes
{
    /**
     * The rights to edit
     * @var BackendPageRights
     */
    private $rights;
    
    /**
     * Inheritabe parent rights
     * @var BackendPageRights
     */
    private $parentRights;
    
    /**
     *
     * @var ContentRights
     */
    protected $contentRights;
    
    /**
     * @param BackendPageRights $parentRights The rights for editing
     * @param BackendPageRights $rights The rights for editing
     * @param string $namePrefix Optional field name prefix
     */
    function __construct(BackendPageRights $parentRights = null, BackendPageRights $rights = null, $namePrefix = '')
    {
        parent::__construct($namePrefix);
        $this->parentRights = $parentRights;
        $this->rights = $rights;
        $beContentRights = $rights ? $rights->GetContentRights() : null;
        $beParentContentRights = $parentRights ? $parentRights->GetContentRights() : null; 
        $this->contentRights = new ContentRights($beParentContentRights, $beContentRights, $namePrefix . 'ContentRights_');
    }
    
    
    /**
     * Saves the page rights
     */
    function Save()
    {
        $this->contentRights->Save();
        if (!$this->rights)
        {
            $this->rights = new BackendPageRights();
        }
        $this->rights->SetCreateIn($this->Value('CreateIn'));
        $this->rights->SetEdit($this->Value('Edit'));
        $this->rights->SetMove($this->Value('Move'));
        $this->rights->SetRemove($this->Value('Remove'));
        $this->rights->SetContentRights($this->contentRights->Rights());
        $this->rights->Save();
    }
    
    /**
     * The label prefix for the checkboxes
     * @return string
     */
    protected function LabelPrefix()
    {
        return 'Core.PageRights.';
    }

    /**
     * Gets the page rights currently edited
     * @return BackendPageRights
     */
    public function Rights()
    {
        return $this->rights;
    }
    
    /**
     * Gets the parent rights
     * @return BackendPageRights
     */
    public function ParentRights()
    {
        return $this->parentRights;
    }

}

