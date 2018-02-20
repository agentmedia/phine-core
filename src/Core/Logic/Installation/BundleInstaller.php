<?php

namespace Phine\Bundles\Core\Logic\Installation;

use Phine\Framework\System\IO\Path;
use Phine\Framework\System\IO\Folder;
use Phine\Framework\System\IO\File;
use Phine\Framework\System\Str;
use Phine\Bundles\Core\Logic\Bundle\BundleManifest;
use Phine\Framework\Database\Interfaces\IDatabaseConnection;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\Database\Exceptions\DatabaseException;
/**
 * The bundle installer base class
 */
class BundleInstaller
{

    /**
     * The manifest of the bundle
     * @var BundleManifest
     */
    private $manifest;

    /**
     * The database connection
     * @var IDatabaseConnection
     */
    private $connection;
    
    /**
     * The version currently installed
     * @var string installedVersion
     */
    private $installedVersion = '';
    
    /**
     * Create a new module installer
     * @param BundleManifest $manifest
     */
    function __construct(BundleManifest $manifest, IDatabaseConnection $connection, $installedVersion = '')
    {
        $this->installedVersion = $installedVersion;
        $this->manifest = $manifest;
        $this->connection = $connection;
    }

    /**
     * Executes all necessary sql scripts
     * 
     */
    function ExecuteSql()
    {
        $versionCompare = version_compare($this->installedVersion, $this->manifest->Version());
       
        if ($versionCompare > 0)
        {
            $bundle = $this->manifest->BundleName();
            throw new \Exception("Error instaling bundle $bundle: Previously installed version is greater than curren code version. Please re-install the bundle.");
        }
        else if ($versionCompare === 0)
        {
            return;
        }
        $engineFolder = $this->FindEngineFolder();
        if (!$engineFolder)
        {
            return;
        }
        $this->ExecuteScripts($engineFolder);       
    }
    
    /**
     * Executes all necessary scripts in the engine folder
     * @param  string $engineFolder The folder
     * @throws \Exception Raises error if any of the scripts fail
     */
    private function ExecuteScripts($engineFolder)
    {
        $files = $this->SortFilesByVersion(Folder::GetFiles($engineFolder));
        
        $completeSql = '';
        foreach ($files as $file)
        {
            $sqlFile = Path::Combine($engineFolder, $file);
            $completeSql .= "\r\n" . File::GetContents($sqlFile);
        }
        try
        {
            $this->CleanAllForeignKeys();
            //$this->connection->StartTransaction();
            $this->connection->ExecuteMultiQuery($completeSql);
        }
        catch (\Exception $exc)
        {
            //$this->connection->RollBack();
            throw $exc;
            //throw new \Exception("Error executing SQL in folder $engineFolder: " . $completeSql, null, $exc);
        }
        //$this->connection->Commit();
    }
    
    private function CleanAllForeignKeys()
    {
        $tables = $this->connection->GetTables();
        foreach ($tables as $table)
        {
            if (Str::StartsWith('pc_' . Str::ToLower($this->manifest->BundleName()  . '_'), $table))
            {
                $this->CleanForeignKeys($table);
            }
        }
    }
    
    /**
     * Cleans all foreign keys of a table
     * @param strign $table The table name
     */
    private function CleanForeignKeys($table)
    {
        $foreignKeys = $this->connection->GetForeignKeys($table);
        foreach ($foreignKeys as $foreignKey)
        {
            $this->connection->DropForeignKey($table, $foreignKey);
        }
    }

    /**
     * 
     * @return string
     * @throws \Exception Raises exception if engine is not supported
     */
    private function FindEngineFolder()
    {
        $bundle = $this->manifest->BundleName();
        $sqlFolder = PathUtil::InstallationSqlFolder($bundle);
        
       
        if (!Folder::Exists($sqlFolder) || Folder::IsEmpty($sqlFolder))
        {
            return '';
        }
        $engine = (string) $this->connection->Engine();
        $engineFolder = Path::Combine($sqlFolder, $engine);
        if (!Folder::Exists($engineFolder))
        {
            throw new \Exception("Bundle $bundle doesn't support your database engine '$engine'");
        }
        return $engineFolder;
    }
    /**
     * Sorts files by version
     * @param string[] $files The files
     * @return string[] Returns the sorted files
     */
    private function SortFilesByVersion(array $files)
    {
        $result = array();
        $foreignKeysFile = '';
        foreach ($files as $file)
        {
            if ($file == 'foreign-keys.sql')
            {
                $foreignKeysFile = $file;
                continue;
            }
            $version = Path::RemoveExtension($file);
            if ($this->installedVersion && version_compare($version, $this->installedVersion) <= 0)
            {
                continue;
            }
            $result[$version] = $file;
        }
        uksort($result, "version_compare");
        if ($foreignKeysFile)
        {
            $result[] = $foreignKeysFile;
        }
        return $result;
    }

}
