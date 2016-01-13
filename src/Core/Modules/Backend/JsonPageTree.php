<?php

namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Bundles\Core\Modules\Backend\Base\JsonTree;
use Phine\Database\Core\Page;
use Phine\Bundles\Core\Logic\Tree\PageTreeProvider;
use Phine\Framework\Webserver\Apache\Htaccess\Writer;
use Phine\Bundles\Core\Logic\Routing\Rewriter;
use Phine\Framework\System\IO\Path;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Hooks\IDeleteHook;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;
use Phine\Bundles\Core\Logic\Module\BackendModule;
use Phine\Database\Core\PageUrl;
use Phine\Database\Access;
use Phine\Framework\Database\Sql\JoinType;

class JsonPageTree extends JsonTree
{
    
    /**
     *
     * @var IDeleteHook[]
     */
    private static $deleteHooks = array();
    
    /**
     * Adds a hook being executed before a page is deleted
     * @param IDeleteHook $hook
     */
    static function AddDeleteHook(IDeleteHook $hook)
    {
        self::$deleteHooks[] = $hook;
    }
    
    protected function TableSchema()
    {
        return Page::Schema();
    }

    protected function TreeProvider()
    {
        return new PageTreeProvider($this->item->GetSite());
    }
    
    /**
     * Remove htaccess page commands before page is deleted
     */
    protected function BeforeDelete()
    {
        foreach (self::$deleteHooks as $hook)
        {
            $hook->BeforeDelete($this->item);
        }
        $logger = new Logger(BackendModule::Guard()->GetUser());
        $logger->ReportPageAction($this->item, Action::Delete());
        
        $file = Path::Combine(PHINE_PATH, 'Public/.htaccess');
        if (!File::Exists($file))
        {
            return;
        }
        $this->UpdateHtaccess($file); 
    }
    
    private function UpdateHtaccess($file)
    {
        $rewriter = new Rewriter(new Writer());
        $text = File::GetContents($file);
        $startPos = strpos($text, (string)$rewriter->PageStartComment($this->item));
        $endPos = false;
        if ($startPos !== false)
        {
            $endPos = strpos($text, (string)$rewriter->PageEndComment($this->item));
            if ($endPos !== false)
            {
                $endPos += strlen((string)$rewriter->PageEndComment($this->item));
            }
        }
        if ($startPos === false || $endPos === false)
        {
            return;
        }
        $newText = substr($text, 0, $startPos) . substr($text, $endPos);
        File::CreateWithText($file, $newText);
    }
   
}
