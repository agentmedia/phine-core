<?php

namespace Phine\Bundles\Core\Snippets\BackendRights;
use App\Phine\Database\Core\BackendSiteRights;
use Phine\Bundles\Core\Snippets\BackendRights\Base\RightsCheckboxes;

/**
 * Displays form elements for site access rights in the backend
 */
class SiteRights extends RightsCheckboxes
{
    /**
     * The rights to edit
     * @var BackendSiteRights
     */
    private $rights;
    
    /**
     * The page rights below the site rights
     * @var PageRights
     */
    protected $pageRights;
    
    /**
     * @param BackendSiteRights $parentRights The parent rights
     * @param BackendSiteRights $rights The rights for editing
     * @param string $namePrefix Optional field name prefix
     */
    function __construct(BackendSiteRights $rights = null, $namePrefix = '')
    {
        parent::__construct($namePrefix);
        $this->rights = $rights;
        $bePageRights = $rights ? $rights->GetPageRights() : null;
        $this->pageRights = new PageRights(null, $bePageRights, $namePrefix . 'SitePageRights_');
    }
    
    
    /**
     * Saves the site rights
     */
    function Save()
    {
        $this->pageRights->Save();
        if (!$this->rights)
        {
            $this->rights = new BackendSiteRights();
        }
        
        $this->rights->SetEdit($this->Value('Edit'));
        $this->rights->SetRemove($this->Value('Remove'));
        $this->rights->SetPageRights($this->pageRights->Rights());
        $this->rights->Save();
    }
    
    /**
     * The label prefix for the checkboxes
     * @return string
     */
    protected function LabelPrefix()
    {
        return 'Core.SiteRights.';
    }

    /**
     * Gets the site rights currently edited
     * @return BackendSiteRights
     */
    public function Rights()
    {
        return $this->rights;
    }
    
    /**
     * Gets the parent rights (always null)
     * @return BackendSiteRights
     */
    public function ParentRights()
    {
        return null;
    }

}

