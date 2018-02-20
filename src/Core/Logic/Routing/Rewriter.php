<?php

namespace Phine\Bundles\Core\Logic\Routing;
use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Site;

use Phine\Framework\Webserver\Apache\Htaccess\RewriteRule;
use Phine\Framework\Webserver\Apache\Htaccess\RewriteCondition;
use Phine\Framework\Webserver\Apache\Htaccess\Enums\FlagType;
use Phine\Framework\Webserver\Apache\Htaccess\CommandFlag;
use Phine\Framework\Webserver\Apache\Htaccess\Variable;
use Phine\Framework\Webserver\Apache\Enums\ServerVariable;
use Phine\Framework\Webserver\Apache\Htaccess\Writer;
use Phine\Framework\Webserver\Apache\Htaccess\CommentLine;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;

class Rewriter
{
    
    const START_COMMENT =  '-- START PHINE GENERATED';
    const END_COMMENT =  '-- END PHINE GENERATED';
    const PARAM_PATTERN = '([A-Za-z0-9\-_]+)';
    const PAGE_URL_PARAM = '--phine-page-url';
    const START_PAGE_COMMENT =  '---- START REWRITE PAGE ID {0}';
    const END_PAGE_COMMENT =  '---- END REWRITE PAGE ID {0}';
    
    /**
     * The htaccess writer
     * @var Writer
     */
    private $writer;
    function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }
    
    /**
     * 
     * @param Page $page
     * @return boolean
     */
    function AddPageCommands(Page $page)
    {
        $pageUrl = $page->GetUrl();
        $params = FrontendRouter::GatherParams($pageUrl);
        if (count($params) == 0)
        {
            return false;
        }
        $this->writer->AddContent($this->PageStartComment($page));
        $this->writer->AddContent($this->SiteHostCondition($page->GetSite()));
        $folderCondition = $this->SiteFolderCondition($page->GetSite());
        if ($folderCondition)
        {
            $this->writer->AddContent($folderCondition);
        }
        $this->writer->AddContent($this->PageRule($page, $params));
        $this->writer->AddContent($this->PageEndComment($page));
        return true;
    }
    
    /**
     * Adds the "generated by phine" start comment
     */
    function AddStartComment()
    {
        $this->writer->AddContent($this->StartComment());
    }
    
    /**
     * The start comment
     * @return CommentLine
     */
    function StartComment()
    {
        return new CommentLine(self::START_COMMENT);
    }
    
    /**
     * Adds the "generated by phine" end comment
     */
    function AddEndComment()
    {
        $this->writer->AddContent($this->EndComment());
    }
    
    /**
     * Returns the end comment line
     * @return CommentLine
     */
    function EndComment()
    {
        return new CommentLine(self::END_COMMENT);
    }
    /**
     * Adds the rules to rewrite anything to the index.php
     */
    function AddIndexCommands()
    {
        $condition = new RewriteCondition('$1', '!^(index\\.php|files|phine|robots\\.txt)');
        $this->writer->AddContent($condition);
        
        $rule = new RewriteRule('(.*)$', 'index.php');
        $rule->AddFlag(new CommandFlag(FlagType::Qsa()));
        $rule->AddFlag(new CommandFlag(FlagType::L()));
        $this->writer->AddContent($rule);
    }
    
    /**
     * Gets the host condition for a page rewrite
     * @param Site $site
     * @return RewriteCondition
     */
    private function SiteHostCondition(Site $site)
    {
        $siteUrl = $site->GetUrl();
        $host = parse_url($siteUrl, PHP_URL_HOST);
        return new RewriteCondition(new Variable(ServerVariable::HttpHost()), $host);
    }
    /**
     * Gets the rewrite condition for the site specific folder; if present
     * @param Site $site
     * @return RewriteCondition
     */
    private function SiteFolderCondition(Site $site)
    {
        $siteUrl = $site->GetUrl();       
        $siteFolder = parse_url($siteUrl, PHP_URL_PATH);
        if ($siteFolder != '' && $siteFolder != '/')
        {
            return new RewriteCondition(new Variable(ServerVariable::RequestUri()), '^' . rtrim($siteFolder, '/') . '/');
        }
        else
        {
            return new RewriteCondition(new Variable(ServerVariable::RequestUri()), '^/');
        } 
        return null;
    }
    /**
     * Returns the page rule
     * @param Page $page
     * @param array $params
     * @return RewriteRule
     */
    private function PageRule(Page $page, array $params)
    {
        $lhs = $page->GetUrl();
        foreach ($params as $param)
        {
            $lhs = str_replace('{' . $param . '}', self::PARAM_PATTERN, $lhs);
        }
        
        $rhs = 'index.php';
        $idx = 1;
        
        foreach ($params as $param)
        {
            $rhs .= $idx > 1 ? '&' : '?';
            $rhs .= $param . '=$' . $idx;
            ++$idx;
        }
        $rhs .= '&' . self::PAGE_URL_PARAM . '=' . $page->GetUrl();
        $rule = new RewriteRule($lhs, $rhs);
        $rule->AddFlag(new CommandFlag(FlagType::Qsa()));
        $rule->AddFlag(new CommandFlag(FlagType::L()));
        return $rule;
    }
    /**
     * The page start comment
     * @param Page $page
     * @return CommentLine
     */
    function PageStartComment(Page $page)
    {
        $text = str_replace('{0}', $page->GetID(), self::START_PAGE_COMMENT);
        return new CommentLine($text);
    }
    
    
    /**
     * The page end comment
     * @param Page $page
     * @return CommentLine
     */
    function PageEndComment(Page $page)
    {
        $text = str_replace('{0}', $page->GetID(), self::END_PAGE_COMMENT);
        return new CommentLine($text);
    }
    
    
}
