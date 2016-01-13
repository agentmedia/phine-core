<?php

namespace Phine\Bundles\Core\Logic\Module;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\Path;
abstract class FrontendJsonModule extends FrontendModule
{
    protected $result;
    public final function AllowChildren()
    {
        return false;
    }
    
    /**
     * 
     * @return type
     */
    protected final function Init()
    {
        $this->result = \json_encode($this->FillObject());
        return parent::Init();
    }
    
    protected function BeforeGather()
    {
        \header('Content-Type: application\json');
        return parent::BeforeGather();
    }
    
    /**
     * Fills a result object
     * @return \stdClass Returns a json encodable object
     */
    protected abstract function FillObject();
    
    /**
     * The simple template with "echo result"
     * @return string
     */
    public function TemplateFile()
    {
        $dir = PathUtil::BundleFolder('Core');
        
        return Path::Combine($dir, 'Templates/EchoResult.phtml');
    }

}
