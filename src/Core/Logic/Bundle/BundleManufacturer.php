<?php
namespace Phine\Bundles\Core\Logic\Bundle;
class BundleManufacturer
{
    private $companyName;
    private $personName;
    
    private $street;
    private $country;
    private $zip;
    private $town;
    
    private $eMail;
    private $phone;
    private $legalNotice;
    private $url;
    
    private function __construct()
    {
        
    }
    
    static function Person($personName)
    {
        $instance = new self();
        $instance->personName = $personName;
        return $instance;
    }
    
    
    static function Company($companyName)
    {
        $instance = new self();
        $instance->companyName = $companyName;
        return $instance;
    }
    
    function SetStreet($street)
    {
        $this->street = $street;
    }
    
    
    function SetCountry($country)
    {
        $this->country= $country;
    }
    
    
    function SetZip($zip)
    {
        $this->zip = $zip;
    }
    
    function SetTown($town)
    {
        $this->town = $town;
    }
    
    function SetEMail($eMail)
    {
        $this->eMail = $eMail;
    }
    
    function SetUrl($url)
    {
        $this->url = $url;
    }
    
    function SetPhone($phone)
    {
        $this->phone = $phone;
    }
    
    
    function SetLegalNotice($legalNotice)
    {
        $this->legalNotice = $legalNotice;
    }
    
    /**
     * Sets The name of the person responsible for the bundle
     * @param string $personName The full name
     */
    function SetPersonName($personName)
    {
        $this->personName = $personName;
    }
    
    /**
     * Gets the name of the person responsible for the bundle
     * @return string Returns the full name of the responsible person
     */
    function GetPersonName()
    {
        return $this->personName;
    }
}

