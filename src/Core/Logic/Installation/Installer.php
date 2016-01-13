<?php
namespace Phine\Bundles\Core\Logic\Installation;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Util\ClassFinder;
use Phine\Framework\Database\Interfaces\IDatabaseConnection;
use Phine\Framework\Database\ObjectGeneration\CamelCaseTableNameMapper;
use Phine\Framework\Database\ObjectGeneration\TableObjectGenerator;
use Phine\Bundles\Core\Logic\Bundle\BundleDependency;
use Phine\Framework\Database\Sql;
use Phine\Database\Core\InstalledBundle;
use Phine\Framework\System\Date;
use Phine\Framework\Progress\Json\Reporter;
use Phine\Framework\Wording\Worder;
use Phine\Framework\Localization\PhpTranslator;

/**
 * The installer for the phine cms
 */
class Installer
{
    /**
     * The bundles already installed as keys, versions as values
     * @var array
     */
    private $installedBundles;
    
    /**
     * The bundles that failed in installation as keys, messages as values
     * @var array
     */
    private $failedBundles;
    
    /**
     * The database connection
     * @var IDatabaseConnection The connection
     */
    private $connection;
    
    /**
     * The amount of all bundles
     * @var int
     */
    private $bundleCount;
    
    /**
     * The reporter
     * @var Reporter
     */
    private $reporter;
    
    /**
     * File for status information
     * @var string
     */
    private $statusFile;
    
    /**
     * Initializes the installer
     * @param IDatabaseConnection $connection The database coonection
     * @param string $statusFile The status file path
     */
    function __construct(IDatabaseConnection $connection, $statusFile)
    {
        $this->installedBundles = array();
        $this->failedBundles = array();
        $this->connection = $connection;
        $this->statusFile = $statusFile;
        $this->reporter = new Reporter($statusFile, array(), 'Phine.Installer.StatusDescription.Sql_{0}.Total_{1}');
        $this->InitTexts();
    }
    
    private function InitTexts()
    {
        $translator = PhpTranslator::Singleton();
        $translator->SetLanguage('en');
        $translator->AddTranslation('en', 'Phine.Installer.StatusDescription.Table_{0}.Total_{1}', 'Creating Database Model {0} of {1}');
        $translator->AddTranslation('en', 'Phine.Installer.StatusDescription.Sql_{0}.Total_{1}', 'Executing Bundle SQL {0} of {1}');
        Worder::SetDefaultRealizer($translator);
    }
    
    private function ReportProgress()
    {
        $progress = count($this->installedBundles) + count($this->failedBundles);
        $this->reporter->Report($progress, $this->bundleCount);
    }
    
    
    function RunSql()
    {
        $this->FetchInstalledBundles();
        $bundles = PathUtil::Bundles();
        $this->bundleCount = count($bundles);
        //Core and builtin bundles must be installed
        if (!$this->InstallBundle('Core'))
        {
            return false;
        }
        $this->ReportProgress();
        if (!$this->InstallBundle('BuiltIn'))
        {
            return false;
        }
        $this->ReportProgress();
        
        foreach ($bundles as $bundle)
        {
            if($bundle != 'Core' && $bundle != 'BuiltIn')
            {
                $this->InstallBundle($bundle);
            }
        }
        return true;
    }
    
    
    private function FetchInstalledBundles()
    {
        try
        {
            $sql = new Sql\Builder($this->connection);
            $tbl = $sql->Table('pc_core_installed_bundle', array('Bundle', 'Version'));
            $fields = $sql->SelectList($tbl->Field('Bundle'));
            $fields->Add($tbl->Field('Version'));
            $select = $sql->Select(false, $fields, $tbl);
            $reader = $this->connection->ExecuteQuery((string)$select);
            while ($reader->Read())
            {
                $this->installedBundles[$reader->ByName('Bundle')] = $reader->ByName('Version');
            }
        }
        catch (\Exception $e)
        {
            //Table just not there...
            return;
        }
        
    }
    
    private function FetchDBNamespaces($tablePrefix)
    {
        $bundles = PathUtil::Bundles();
        $namespaces = array();
        foreach ($bundles as $bundle)
        {
            $namespaces[$tablePrefix . strtolower($bundle) . '_'] = $bundle;
        }
        return $namespaces;
    }
    
    /**
     * (Re-)Creates the database
     */
    function CreateDBObjects()
    {
        $tablePrefix = 'pc_';
      
        
        $mapper = new CamelCaseTableNameMapper('Phine\Database', $this->FetchDBNamespaces($tablePrefix));
        $generator = new TableObjectGenerator($this->connection, PathUtil::DatabaseFolder(), $mapper);
        $generator->AddReporter(new Reporter($this->statusFile, array(), 'Phine.Installer.StatusDescription.Table_{0}.Total_{1}'));
        $generator->Generate();
        $this->UpdateBundleVersions();        
    }
    
    
    /**
     * Updates database to store current bundle versions
     */
    private function UpdateBundleVersions()
    {
        $this->ClearInstalledBundles();
        $bundles = PathUtil::Bundles();
        foreach ($bundles as $bundle)
        {
            if (!array_key_exists($bundle, $this->failedBundles))
            {
                $this->UpdateBundleVersion($bundle);
            }    
        }
    }
    
    /**
     * Updates the version of the bundle
     * @param string $bundle The bundle name
     */
    private function UpdateBundleVersion($bundle)
    {
        $instBundle = InstalledBundle::Schema()->ByBundle($bundle);
        if (!$instBundle)
        {
            $instBundle = new InstalledBundle();
        }
        $instBundle->SetVersion($this->installedBundles[$bundle]);
        $instBundle->SetBundle($bundle);
        $instBundle->SetLastUpdate(Date::Now());
        $instBundle->Save();
    }
    
    
    /**
     * Clears installed bundles
     */
    private function ClearInstalledBundles()
    {
        //Clear bundles without code folder
        $bundles = PathUtil::Bundles();
        $sql = new Sql\Builder($this->connection);
        $inList = $sql->InListFromValues($bundles);
        $tbl = InstalledBundle::Schema()->Table();
        
        $where = $sql->NotIn($tbl->Field('Bundle'), $inList);
        InstalledBundle::Schema()->Delete($where);
        //Clear failed bundles
        $failedList = $sql->InListFromValues(array_keys($this->failedBundles));
        if ($failedList)
        {
            InstalledBundle::Schema()->Delete($sql->In($tbl->Field('Bundle'), $failedList));
        }
    }
    /**
     * Gets the currently installed version of the bundle
     * @param string $bundle The bundle name
     */
    private function InstalledVersion($bundle)
    {
        if (array_key_exists($bundle, $this->installedBundles))
        {
            return $this->installedBundles[$bundle];
        }
        return '';
    }
    /**
     * Installs a single bundle
     * @param string $bundle
     * @return boolean
     */
    private function InstallBundle($bundle)
    {
        $manifest = ClassFinder::Manifest($bundle);
        foreach ($manifest->Dependencies() as $dependency)
        {
            if (!$this->InstallDependency($dependency))
            {
                return false;
            }
        }
        $bundleInstaller = new BundleInstaller($manifest, $this->connection, $this->InstalledVersion($bundle));
        $result = true;
        try
        {
            $bundleInstaller->ExecuteSql();
            $this->installedBundles[$bundle] = $manifest->Version();
        }
        catch (\Exception $exc)
        {
            $this->failedBundles[$bundle] = $exc->getMessage();
            $result = false;
        }
        $this->ReportProgress();
        return $result;
    }
    /**
     * Installs a dependency
     * @param BundleDependency $dependency
     * @return boolean
     */
    private function InstallDependency(BundleDependency $dependency)
    {
        $bundle = $dependency->BundleName();
        if (array_key_exists($bundle, $this->failedBundles))
        {
            return false;
        }
        $installedVersion = $this->InstalledVersion($bundle);
        $manifest = ClassFinder::Manifest($bundle);
        if (!$installedVersion || version_compare($installedVersion, $manifest->Version()) < 0)
        {
            if (!$this->InstallBundle($bundle))
            {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Returns the failed bundles
     * @return array Returns the bundles that failed as keys, messages as values
     */
    function FailedBundles()
    {
        return $this->failedBundles;
    }
}
