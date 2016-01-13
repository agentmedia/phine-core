<?php

namespace Phine\Bundles\Core\Logic\Caching;
use Phine\Framework\System\Date;
use Phine\Framework\System\IO\File;

/**
 * Implement helper methods for caching contents in files
 */
class FileCacher
{
    /**
     * The cache file path
     * @var string 
     */
    private $file;
    
    /**
     * The cache lifetime, in seconds
     * @var int
     */
    private $cacheLifetime;
    
    /**
     * Creates the file cacher
     * @param string $file The full path to the cache file
     * @param int $cacheLifetime The cache lifetime, in seconds
     */
    function __construct($file, $cacheLifetime)
    {
        $this->file = $file;
        $this->cacheLifetime = $cacheLifetime;
    }
    
    /**
     * Determines whether generated content must be stored in cache
     * @return boolean Returns true if cache lifetime is not zero.
     */
    function MustStoreToCache()
    {
        return $this->cacheLifetime > 0;
    }
    
    /**
     * Calculates if the cache content needs to be used
     * @return boolean
     */
    function MustUseCache()
    {
        if ($this->cacheLifetime == 0)
        {
            return false;
        }
        if (!File::Exists($this->file))
        {
            return false;
        }
        $now = Date::Now();
        $lastMod = File::GetLastModified($this->file);
        if ($now->TimeStamp() - $lastMod->TimeStamp() < $this->cacheLifetime)
        {
            return true;
        }
        return false;
    }
    
    /**
     * Stores the content in the cache file
     * @param string $content The content to cache
     */
    function StoreToCache($content)
    {
        File::CreateWithText($this->file, $content);
    }
    
    /**
     * Pulls content from the cache
     * @return string Gets the contents of the cache file
     */
    function GetFromCache()
    {
        return File::GetContents($this->file);
    }
}
