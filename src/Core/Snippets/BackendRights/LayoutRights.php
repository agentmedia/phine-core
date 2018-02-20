<?php

namespace Phine\Bundles\Core\Snippets\BackendRights;
use App\Phine\Database\Core\BackendLayoutRights;
use Phine\Bundles\Core\Snippets\BackendRights\Base\RightsCheckboxes;

/**
 * Displays form elements for layout access rights in the backend
 */
class LayoutRights extends RightsCheckboxes
{
    /**
     * The rights to edit
     * @var BackendLayoutRights
     */
    private $rights;
    
    /**
     * The snippet for the content rights on the layout
     * @var ContentRights
     */
    protected $contentRights;
    
    /**
     * Creates a layout rights checkbox snippet
     * @param BackendLayoutRights $rights The rights for editing
     * @param string $namePrefix Optional field name prefix
     */
    function __construct(BackendLayoutRights $rights = null, $namePrefix = '')
    {
        parent::__construct($namePrefix);
        $this->rights = $rights;
        $beContentRights = $rights ? $rights->GetContentRights() : null;
        
        $this->contentRights = new ContentRights(null, $beContentRights, $namePrefix . 'LayoutContentRights_');
    }
    
    
    /**
     * Saves the layout rights
     */
    function Save()
    {
        $this->contentRights->Save();
        if (!$this->rights)
        {
            $this->rights = new BackendLayoutRights();
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
        return 'Core.LayoutRights.';
    }

    /**
     * Gets the layout rights currently edited
     * @return BackendLayoutRights
     */
    public function Rights()
    {
        return $this->rights;
    }
    
    /**
     * Gets the parent rights
     * @return BackendLayoutRights
     */
    public function ParentRights()
    {
        return null;
    }

}

