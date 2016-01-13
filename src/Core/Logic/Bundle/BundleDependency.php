<?php

namespace Phine\Bundles\Core\Logic\Bundle;

class BundleDependency
{
    /**
     * The name of the bundle the current bundle dependents on
     * @var string
     */
    private $bundleName;
    
    /**
     * The first version the of the dependency tested with this bundle
     * @var string
     */
    private $versionFrom;
    
    /**
     * The latest version of the dependency tested with this bundle
     * @var string
     */
    private $versionTo;
    
    function __construct($bundleName, $versionFrom, $versionTo)
    {
        $this->bundleName = $bundleName;
        $this->versionFrom = $versionFrom;
        $this->versionTo = $versionTo;
    }
    
    /**
     * The bundle name of the dependency
     * @return string
     */
    function BundleName()
    {
        return $this->bundleName;
    }
    
    /**
     * The first deppendeny version tested with this bundle
     * @return string Returns the frts tested version of the dependency
     */
    function VersionFrom()
    {
        return $this->versionFrom;
    }
    
    /**
     * The last deppendeny version tested with this bundle
     * @return string Returns the latest tested version of the dependency
     */
    function VersionTo()
    {
        return $this->versionTo;
    }
}

