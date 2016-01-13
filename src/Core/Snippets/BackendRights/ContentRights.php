<?php

namespace Phine\Bundles\Core\Snippets\BackendRights;
use Phine\Database\Core\BackendContentRights;
use Phine\Bundles\Core\Snippets\BackendRights\Base\RightsCheckboxes;

/**
 * Displays form elements for content access rights in the backend
 */
class ContentRights extends RightsCheckboxes
{
    /**
     * The rights to edit
     * @var BackendContentRights
     */
    private $rights;
    
    /**
     * Inheritabe parent rights
     * @var BackendContentRights
     */
    private $parentRights;
    
    /**
     * @param BackendContentRights $parentRights The rights for editing
     * @param BackendContentRights $rights The rights for editing
     * @param string $namePrefix Optional field name prefix
     */
    function __construct(BackendContentRights $parentRights = null, BackendContentRights $rights = null, $namePrefix = '')
    {
        parent::__construct($namePrefix);
        $this->parentRights = $parentRights;
        $this->rights = $rights;
    }
    
    /**
     * Saves the content rights
     */
    function Save()
    {
        if (!$this->rights)
        {
            $this->rights = new BackendContentRights();
        }
        $this->rights->SetCreateIn($this->Value('CreateIn'));
        $this->rights->SetEdit($this->Value('Edit'));
        $this->rights->SetMove($this->Value('Move'));
        $this->rights->SetRemove($this->Value('Remove'));
        $this->rights->Save();
    }
    
    /**
     * The label prefix for the checkboxes
     * @return string
     */
    protected function LabelPrefix()
    {
        return 'Core.ContentRights.';
    }

    /**
     * Gets the content rights currently added
     * @return BackendContentRights
     */
    public function Rights()
    {
        return $this->rights;
    }
    
    /**
     * GEts the parent content rights for inheritance
     * @return BackendContentRights
     */
    public function ParentRights()
    {
        return $this->parentRights;
    }

}

