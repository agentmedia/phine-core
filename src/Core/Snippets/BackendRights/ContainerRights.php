<?php

namespace Phine\Bundles\Core\Snippets\BackendRights;
use Phine\Database\Core\BackendContainerRights;
use Phine\Bundles\Core\Snippets\BackendRights\Base\RightsCheckboxes;

/**
 * Displays form elements for container access rights in the backend
 */
class ContainerRights extends RightsCheckboxes
{
    /**
     * The rights to edit
     * @var BackendContainerRights
     */
    private $rights;
    
    
    /**
     * The snippet for the content rights on the container
     * @var ContentRights
     */
    protected $contentRights;
    
    /**
     * 
     * Creates a new instance of the backend container rights form elements
     * @param BackendContainerRights $rights The rights for editing
     * @param string $namePrefix Optional field name prefix
     */
    function __construct(BackendContainerRights $rights = null, $namePrefix = '')
    {
        parent::__construct($namePrefix);
        $this->rights = $rights;
        $beContentRights = $rights ? $rights->GetContentRights() : null;
        $this->contentRights = new ContentRights(null, $beContentRights, $namePrefix . 'ContainerContentRights_');
    }
    
    
    /**
     * Saves the container rights
     */
    function Save()
    {
        $this->contentRights->Save();
        if (!$this->rights)
        {
            $this->rights = new BackendContainerRights();
        }
        $this->rights->SetEdit($this->Value('Edit'));
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
        return 'Core.ContainerRights.';
    }

    /**
     * Gets the container rights currently edited
     * @return BackendContainerRights
     */
    public function Rights()
    {
        return $this->rights;
    }
    
    /**
     * Gets the parent rights (always null)
     * @return BackendContainerRights
     */
    public function ParentRights()
    {
        return null;
    }

}

