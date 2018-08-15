<?php

namespace Phine\Bundles\Core\Logic\InsertVariables;

use App\Phine\Database\Core\Page;
use App\Phine\Database\Core\Site;
use Phine\Framework\System\Http\Request;
use Phine\Bundles\Core\Logic\Rendering\PageRenderer;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\File;
use Phine\Framework\System\Date;
use Phine\Framework\System\IO\Path;

class Replacer {

    /**
     * the text to process
     * @var string
     */
    private $text = '';

    /**
     * The variables reader
     * @var Reader
     */
    private $reader;

    function __construct() {
        $this->reader = new Reader();
    }

    function RealizeVariables($text) {
        $this->text = $text;
        $startPos = $this->reader->NextTokenStart($this->text, 0);

        $endPos = $startPos;
        while ($startPos !== false) {
            $token = $this->reader->ParseToken($this->text, $startPos, $endPos);

            if ($token) {
                $this->ReplaceToken($token, $startPos, $endPos);
            }
            $startPos = $this->reader->NextTokenStart($this->text, $endPos);
        }
        return $this->text;
    }

    private function ReplaceToken(Token $token, $startPos, &$endPos) {
        switch ($token->Type) {
            case 'page':
                $this->ReplacePageToken($token, $startPos, $endPos);
                break;

            case 'file':
                $this->ReplaceFileToken($token, $startPos, $endPos);
                break;

            case 'site':
                $this->ReplaceSiteToken($token, $startPos, $endPos);
                break;
        }
    }

    private function ReplaceFileToken(Token $token, $startPos, &$endPos) {

        $path = $token->TypeParam('path');
        if (!$path) {
            return;
        }
        $serverPath = Path::Combine(PathUtil::FilesPath(), $path);
        if (!File::Exists($serverPath)) {

            throw new \Exception(Trans('Core.Replacer.Error.FileNotFound.Path_{0}', $path));
        }
        if (!File::IsReadable($serverPath)) {
            throw new \Exception(Trans('Core.Replacer.Error.FileNotReadable.Path_{0}', $path));
        }

        switch ($token->Property) {
            case 'lastmodurl':
                $this->ReplaceFileLastModUrl($serverPath, $token, $startPos, $endPos);
                break;

            case 'nocacheurl':
                $this->ReplaceFileNoCacheUrl($token, $startPos, $endPos);
                break;
        }
    }

    private function ReplaceFileLastModUrl($serverPath, Token $token, $startPos, &$endPos) {
        $url = Path::Combine('files', $token->TypeParam('path'));
        $lastMod = File::GetLastModified($serverPath)->TimeStamp();
        $this->InsertValue($url . '?ts=' . $lastMod, $token, $startPos, $endPos);
    }

    private function ReplaceFileNoCacheUrl(Token $token, $startPos, &$endPos) {
        $url = Path::Combine('files', $token->TypeParam('path'));
        $tsNow = Date::Now()->TimeStamp();
        $this->InsertValue($url . '?ts=' . $tsNow, $token, $startPos, $endPos);
    }

    private function ReplacePageToken(Token $token, $startPos, &$endPos) {
        $page = $this->FindPage($token);
        switch ($token->Property) {
            case 'url':
                $this->ReplacePageUrl($page, $token, $startPos, $endPos);
                break;

            case 'title':
                $this->ReplacePageTitle($page, $token, $startPos, $endPos);
                break;

            case 'description':
                $this->ReplacePageDescription($page, $token, $startPos, $endPos);
                break;

            case 'keywords':
                $this->ReplacePageKeywords($page, $token, $startPos, $endPos);
                break;

            case 'name':
                $this->ReplacePageName($page, $token, $startPos, $endPos);
                break;
        }
    }

    private function ReplaceSiteToken(Token $token, $startPos, &$endPos) {
        $site = $this->FindSite($token);

        switch ($token->Property) {
            case 'url':
                $this->ReplaceSiteUrl($site, $token, $startPos, $endPos);
                break;
            
            case 'baseUrl':
                $this->ReplaceSiteBaseUrl($site, $token, $startPos, $endPos);
                break;
        }
    }

    private function ReplaceSiteUrl(Site $site, Token $token, $startPos, &$endPos) {
        $this->InsertValue($site->GetUrl(), $token, $startPos, $endPos);
    }
    
    private function ReplaceSiteBaseUrl(Site $site, Token $token, $startPos, &$endPos) {
        $baseUrl = $site->GetBaseUrl();
        if (!$baseUrl) {
            //intelligent guess for base url
            $siteUrl = rtrim($site->GetUrl(), '/');
            if (trim(parse_url($siteUrl, PHP_URL_PATH)) !== '') {
                //most likely for local sites or language subfolders: take site url´s parent directory
                $baseUrl = Path::Directory($siteUrl) . '/';
            }
            else {
                //for simple urls without any folder path like https://example.com
                $baseUrl = $siteUrl . '/';
            }
        }
         
        $this->InsertValue($baseUrl, $token, $startPos, $endPos);
    }

    private function FindSite(Token $token) {
        $idSite = $this->SiteByID($token->TypeParam('id'));
        if ($idSite) {
            return $idSite;
        }
        return PageRenderer::Page()->GetSite();
    }

    private function SiteByID($id) {
        if (!$id) {
            return null;
        }
        $site = Site::Schema()->ByID($id);
        if (!$site) {
            throw new \Exception(Trans('Core.Replacer.Error.SiteNotFound.ID_{0}', $id));
        }
        return $site;
    }

    private function FindPage(Token $token) {
        $idPage = $this->PageByID($token->TypeParam('id'));

        if ($idPage) {
            return $idPage;
        }
        return PageRenderer::Page();
    }

    private function PageByID($id) {
        if (!$id) {
            return null;
        }
        $page = Page::Schema()->ByID($id);
        if (!$page) {
            throw new \Exception(Trans('Core.Replacer.Error.PageNotFound.ID_{0}', $id));
        }
        return $page;
    }

    private function ReplacePageTitle(Page $page, Token $token, $startPos, &$endPos) {
        $value = $page->GetTitle();
        if ($page->Equals(PageRenderer::Page())) {
            $value = PageRenderer::$Title;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }

    private function ReplacePageDescription(Page $page, Token $token, $startPos, &$endPos) {
        $value = $page->GetDescription();
        if ($page->Equals(PageRenderer::Page())) {
            $value = PageRenderer::$Description;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }

    private function ReplacePageKeywords(Page $page, Token $token, $startPos, &$endPos) {
        $value = $page->GetKeywords();
        if ($page->Equals(PageRenderer::Page())) {
            $value = PageRenderer::$Keywords;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }

    private function ReplacePageName(Page $page, Token $token, $startPos, &$endPos) {
        $this->InsertValue($page->GetName(), $token, $startPos, $endPos);
    }

    private function ReplacePageUrl(Page $page, Token $token, $startPos, &$endPos) {
        $params = $token->PropertyParams;
        if ($page->Equals(PageRenderer::Page())) {
            //merge current GET parameters on current page
            $params = array_merge(Request::GetArray(), $params);
        }
        $url = FrontendRouter::PageUrl($page, $params);
        $this->InsertValue($url, $token, $startPos, $endPos);
    }

    private function InsertValue($value, Token $token, $startPos, &$endPos) {
        $replacement = $this->ApplyFilters($token->Filters, $value);
        $this->text = substr($this->text, 0, $startPos) . $replacement .
                substr($this->text, $endPos);
        $endPos = $startPos + strlen($replacement);
    }

    private function ApplyFilters(array $filters, $value) {
        $isRaw = false;
        foreach ($filters as $filter) {
            if ($filter == 'raw') {
                $isRaw = true;
                continue;
            }
            if (!function_exists($filter)) {
                throw new \Exception(Trans('Core.Replacer.Error.FilterNotFound.Name_{0}', $filter));
            }
            $value = $filter($value);
        }
        return $isRaw ? $value : Html($value);
    }

}
