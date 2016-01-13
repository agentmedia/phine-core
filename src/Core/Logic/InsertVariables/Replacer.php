<?php
namespace Phine\Bundles\Core\Logic\InsertVariables;
use Phine\Database\Core\Page;
use Phine\Framework\System\Http\Request;

use Phine\Bundles\Core\Logic\Rendering\PageRenderer;
use Phine\Bundles\Core\Logic\Routing\FrontendRouter;
use Phine\Database\Access;

class Replacer
{
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
    function __construct()
    {
        $this->reader = new Reader();
    }
    
    
    function RealizeVariables($text)
    {
        $this->text = $text;
        $startPos = $this->reader->NextTokenStart($this->text, 0);
        
        $endPos = $startPos;
        while ($startPos !== false)
        {
            $token = $this->reader->ParseToken($this->text, $startPos, $endPos);
           
            if ($token)
            {
                $this->ReplaceToken($token, $startPos, $endPos);
            }
            $startPos = $this->reader->NextTokenStart($this->text, $endPos);
        }
        return $this->text;
    }
    
    private function ReplaceToken(Token $token, $startPos, &$endPos)
    {
        switch ($token->Type)
        {
            case 'page':
                $this->ReplacePageToken($token, $startPos, $endPos);
                return;
        }
    }
    
    private function ReplacePageToken(Token $token, $startPos, &$endPos)
    {
        $page = $this->FindPage($token);
        switch ($token->Property)
        {
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
    
    private function FindPage(Token $token)
    {
        $idPage = $this->PageByID($token->TypeParam('id'));
        
        if ($idPage)
        {
            return $idPage;
        }
        return PageRenderer::Page();
    }
    
    private function PageByID($id)
    {
        if (!$id)
        {
            return null;
        }
        $page = Page::Schema()->ByID($id);
        if (!$page)
        {
            throw new \Exception(Trans('Core.Replacer.Error.PageNotFound.ID_{0}', $id));
        }
        return $page;
    }
    
    private function ReplacePageTitle(Page $page, Token $token, $startPos, &$endPos)
    {
        $value = $page->GetTitle();
        if ($page->Equals(PageRenderer::Page()))
        {
            $value = PageRenderer::$Title;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }
    
    private function ReplacePageDescription(Page $page, Token $token, $startPos, &$endPos)
    {
        $value = $page->GetDescription();
        if ($page->Equals(PageRenderer::Page()))
        {
            $value = PageRenderer::$Description;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }
    
    private function ReplacePageKeywords(Page $page, Token $token, $startPos, &$endPos)
    {
        $value = $page->GetKeywords();
        if ($page->Equals(PageRenderer::Page()))
        {
            $value = PageRenderer::$Keywords;
        }
        $this->InsertValue($value, $token, $startPos, $endPos);
    }
    
    private function ReplacePageName(Page $page, Token $token, $startPos, &$endPos)
    {
        $this->InsertValue($page->GetName(), $token, $startPos, $endPos);
    }
    
    
    private function ReplacePageUrl(Page $page, Token $token, $startPos, &$endPos)
    {
        $params = $token->PropertyParams;
        if ($page->Equals(PageRenderer::Page()))
        {
            //merge current GET parameters on current page
            $params = array_merge(Request::GetArray(), $params);
        }
        $url = FrontendRouter::PageUrl($page, $params);
        $this->InsertValue($url, $token, $startPos, $endPos);
    }
    
    private function InsertValue($value, Token $token, $startPos, &$endPos)
    {
        $replacement = $this->ApplyFilters($token->Filters, $value);
        $this->text = substr($this->text, 0, $startPos) . $replacement .
                substr($this->text, $endPos);
        $endPos = $startPos + strlen($replacement);
    }

    private function ApplyFilters(array $filters, $value)
    {
        $isRaw = false;
        foreach ($filters as $filter)
        {
            if ($filter == 'raw')
            {
                $isRaw = true;
                continue;
            }
            if (!function_exists($filter))
            {
                throw new \Exception(Trans('Core.Replacer.Error.FilterNotFound.Name_{0}', $filter));
            }
            $value = $filter($value);
        }
        return $isRaw ? $value : Html($value);
    }
    
    
    
    
    
}
    
