<?php

namespace Phine\Bundles\Core;
use Phine\Bundles\Core\Logic\Bundle\BundleManifest;
use Phine\Bundles\Core\Logic\Bundle\BundleManufacturer;
use Phine\Framework\System\IO\Path;

class Manifest extends BundleManifest
{
    /**
     * The version
     * @return string Returns the bundle version
     */
    public function Version()
    {
        return '1.2.7';
    }
    
    /**
     * Loads extra code not available by autoload
     */
    protected function LoadBackendCode()
    {
        require_once Path::Combine(__DIR__, 'Globals/Functions.php');
    }
    
    /**
     * Returns the bundle manufacturer
     * @return BundleManufacturer
     */
    public function Manufacturer()
    {
        $myCompany = BundleManufacturer::Company('agent media - Klaus Potzesny');
        $myCompany->SetEMail('info@agent-media.com');
        $myCompany->SetUrl('http://agent-media.com');
        $myCompany->SetZip('50354');
        $myCompany->SetCountry('Deutschland');
        $myCompany->SetTown('Hürth');
        $myCompany->SetStreet('Brunhildstr. 1');
        $myCompany->SetPhone('02233/7128442');
        $myCompany->SetLegalNotice('USTID-Nr: DE246909470');
        return $myCompany;
    }
    /**
     * The dependencies; core must not depend on other bundles
     * @return array Returns an empty array
     */
    public function Dependencies()
    {
        return array();
    }

    /**
     * Loads Code not available to autoload
     */
    protected function LoadFrontendCode()
    {
        require_once Path::Combine(__DIR__, 'Globals/Functions.php');
    }

}

