<?php

namespace Phine\Bundles\Core\Logic\Routing;

use Phine\Framework\System\StringReader;
use App\Phine\Database\Core\Page;
use Phine\Framework\System\IO\Path;
use App\Phine\Database\Core\PageUrl;
use Phine\Bundles\Core\Logic\Tree\PageParamListProvider;
use App\Phine\Database\Access;
use App\Phine\Database\Core\Site;
use Phine\Bundles\Core\Logic\DBEnums\PageType;
use Phine\Framework\System\Http\Request;

/**
 * Router for frontend urls
 */
class FrontendRouter
{

    /**
     * Gathers the obligatory parameters from the url
     * @param string $url The page url
     * @return string[] Array of parameter names
     */
    static function GatherParams($url)
    {
        $reader = new StringReader($url);
        $param = '';
        $params = array();
        $start = false;
        while (false !== ($ch = $reader->ReadChar())) {
            if ($ch == '{') {
                $start = true;
            }
            else if ($ch == '}' && $start) {
                $params[] = $param;
                $param = '';
                $start = false;
            }
            else if ($start) {
                $param .= $ch;
            }
        }
        return $params;
    }

    /**
     * Returns the url of a page with parameters and fragment
     * @param Page $page The page
     * @param array $params All parameters
     * @return string Return the page url
     */
    static function PageUrl(Page $page, array $params = array(), $fragment = '')
    {
        $siteUrl = $page->GetSite()->GetUrl();
        $pageUrl = $page->GetUrl();
        if ($pageUrl == 'index.html') {
            $url = $siteUrl;
        }
        else {
            $url = Path::Combine($siteUrl, $pageUrl);
        }

        $oblParams = self::GatherParams($url);
        $urlObl = self::AttachObligatoryParams($url, $oblParams, $params);

        $urlAllParams = self::AttachMoreParams($urlObl, $oblParams, $params);
        return $fragment ? $urlAllParams . '#' . $fragment : $urlAllParams;
    }

    /**
     * Gets the url for a page url entity
     * @param PageUrl $pageUrl The page url as stored in the database
     * @param array $additionalParameters Additional parameters; will override stored parameters with same keys
     * @return string Returns the page url
     */
    static function Url(PageUrl $pageUrl, array $additionalParameters = array())
    {
        $list = new PageParamListProvider($pageUrl);
        $params = $list->ToArray();
        foreach ($additionalParameters as $key => $value) {
            $params[$key] = $value;
        }
        return self::PageUrl($pageUrl->GetPage(), $params, $pageUrl->GetFragment());
    }

    /**
     * Attaches obligatory parameters
     * @param string $url The url pattern as given by the associated page property
     * @param array $oblParams The names of the obligatory parameters
     * @param array $params All given parameters for appending
     * @return string Returns the url with obligatory paramters attached
     * @throws \LogicException Raises an error if an obligatory parameter is missing
     */
    private static function AttachObligatoryParams($url, array $oblParams, array $params)
    {
        foreach ($oblParams as $oblParam) {

            $value = '';
            if (!array_key_exists($oblParam, $params)) {
                $value = Request::GetData($oblParam);
            }
            else {
                $value = $params[$oblParam];
            }
            if ($value) {
                $url = str_replace('{' . $oblParam . '}', $value, $url);
            }
        }
        return $url;
    }

    /**
     * Attaches none oblique url paramters
     * @param string $url The url with obligatory parameters already attached
     * @param array $oblParams The names of the obligatory parameters
     * @param array $params All url parameters
     * @return string Returns the url with all paramteters attached
     */
    private static function AttachMoreParams($url, array $oblParams, array $params)
    {
        $moreParams = array();
        foreach ($params as $key => $value) {
            if (!in_array($key, $oblParams)) {
                $moreParams[$key] = $value;
            }
        }
        if (count($moreParams)) {
            $url .= '?' . http_build_query($moreParams, null, '&');
        }
        return $url;
    }

    /**
     * Finds the 404 page for a site
     * @param Site $site The site whise 404 page is searched for
     * @return Page The 404 page
     */
    static function Page404(Site $site)
    {
        $sql = Access::SqlBuilder();
        $tblPage = Page::Schema()->Table();
        $where = $sql->Equals($tblPage->Field('Type'), $sql->Value((string) PageType::NotFound()))
                ->And_($sql->Equals($tblPage->Field('Site'), $sql->Value($site->GetID())));

        return Page::Schema()->First($where);
    }

}
