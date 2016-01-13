<?php

namespace Phine\Bundles\Core\Logic\Sitemap;

use Phine\Framework\Sitemap\XmlGenerator;
use Phine\Framework\Sitemap\Enums\ChangeFrequency;
use Phine\Database\Core\Site;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use Phine\Database\Core\Page;
use Phine\Bundles\Core\Logic\Access\Frontend\MemberGuard;
use Phine\Framework\Access\Base\Action;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Bundles\Core\Logic\Caching\FileCacher;
use Phine\Bundles\Core\Logic\Logging\Interfaces\IContainerReferenceResolver;
use Phine\Bundles\Core\Logic\Logging\LogEvaluator;
use Phine\Bundles\Core\Logic\DBEnums\PageType;
class Generator
{

    /**
     * The generator for the sitemap
     * @var XmlGenerator 
     */
    private $sitemap;

    /**
     * The page tree provider
     * @var PageTreeProvider
     */
    private $pageTree;

    /**
     *
     * @var MemberGuard
     */
    private $guard;

    /**
     * The site
     * @var Site
     */
    private $site;
    
    /**
     * The file cacher for the sitemap
     * @var FileCacher
     */
    private $cacher;
    
    /**
     * The sitemap xml
     * @var string
     */
    private $xml;
    
    /**
     * A resolver for content container reference
     * @var IContainerReferenceResolver
     */
    private $resolver;
    
    /**
     * Creates a new generator
     * @param Site $site The site
     * @param IContainerReferenceResolver $resolver A resolver to get last page mod date even over container contents
     */
    function __construct(Site $site, IContainerReferenceResolver $resolver)
    {
        $this->resolver = $resolver;
        $this->site = $site;
        $this->cacher = new FileCacher(PathUtil::SitemapCacheFile($site), $site->GetSitemapCacheLifetime());
        $this->sitemap = new XmlGenerator();
        $this->pageTree = new PageTreeProvider($site);
        $this->guard = new MemberGuard();
    }

    /**
     * Generates the xml by pulling it from cache or generating it
     * @return string
     */
    function Generate()
    {
        if ($this->cacher->MustUseCache())
        {
            $this->xml = $this->cacher->GetFromCache();
            return;
        }
        $this->AddAllPages();
        $this->xml = $this->sitemap->SaveXml();
        if ($this->cacher->MustStoreToCache())
        {
            $this->cacher->StoreToCache($this->xml);
        }
    }
    /**
     * The entry point for fetching all pages as sitemap
     */
    private function AddAllPages()
    {
        $page = $this->pageTree->TopMost();
        while ($page)
        {
            $this->AddPageBranch($page);
            $page = $this->pageTree->NextOf($page);
        }
    }
    
    
    /**
     * Adds a page and all of its children
     * @param Page $page The current page
     */
    private function AddPageBranch(Page $page)
    {
        $this->AddPage($page);
        $child = $this->pageTree->FirstChildOf($page);
        while ($child)
        {
            $this->AddPageBranch($child);
            $child = $this->pageTree->NextOf($child);
        }
    }

    /**
     * Adds a single page to the sitemap
     * @param Page $page The page
     */
    private function AddPage(Page $page)
    {
        if (!$this->PageAllowed($page))
        {
            return;
        }
        $params = FrontendRouter::GatherParams($page->GetUrl());
        if (count($params) == 0)
        {
            $changeFreq = ChangeFrequency::ByValue($page->GetSitemapChangeFrequency());
            $priority = $page->GetSitemapRelevance();
            $lastLog = LogEvaluator::LastPageModLog($page, $this->resolver);
            $lastMod = $lastLog ? $lastLog->GetChanged() : null;
            $this->sitemap->AddUrl(FrontendRouter::PageUrl($page), $changeFreq, $priority, $lastMod);
        }
    }

    private function PageAllowed(Page $page)
    {
        if ($page->GetType() != (string)PageType::Normal() ||
                $page->GetSitemapRelevance() == 0)
        {
            return false;
        }
        return $this->guard->Allow(Action::Read(), $page);
    }

    function GetXml()
    {
        return $this->xml;
    }

}
