<?php
namespace Phine\Bundles\Core\Modules\Backend;

use Phine\Framework\System\Http\Request;
use Phine\Framework\System\Http\Response;

use Phine\Framework\FormElements\Fields\Textarea;
use Phine\Bundles\Core\Logic\Module\BackendForm;
use Phine\Database\Core\Layout;
use Phine\Bundles\Core\Logic\Routing\BackendRouter;
use Phine\Bundles\Core\Logic\Util\PathUtil;
use Phine\Framework\System\IO\File;
use Phine\Bundles\Core\Logic\Logging\Logger;
use Phine\Bundles\Core\Logic\Logging\Enums\Action;

/**
 * The form for a layout template
 */
class LayoutTemplateForm extends BackendForm
{
    /**
     *
     * @var Layout;
     */
    private $layout;
    
    private $file;
    
    private $contents;
    protected function BeforeInit()
    {
        $this->layout = Layout::Schema()->ByID(Request::GetData('layout'));
        
        if (!$this->layout)
        {
            //TODO: Message
            Response::Redirect($this->BackLink());
        }
        $this->file = PathUtil::LayoutTemplate($this->layout);
        if (!File::Exists($this->file))
        {
            //TODO: Message
            Response::Redirect($this->BackLink());
        }
        $this->contents = File::GetContents($this->file);
        return parent::BeforeInit();
    }
    
    protected function Init()
    {
        $this->AddContentsField();
        $this->AddSubmit();
        return parent::Init();
    }
    private function AddContentsField()
    {
        $name = 'Contents';
        $field = new Textarea($name, $this->contents);
        $this->AddField($field);
    }
    
    protected function OnSuccess()
    {
        File::CreateWithText($this->file, $this->Value('Contents', false));
        $logger = new Logger(self::Guard()->GetUser());
        $logger->ReportLayoutAction($this->layout, Action::Update());
        Response::Redirect($this->BackLink());
    }
    
    protected function BackLink()
    {
        return BackendRouter::ModuleUrl(new LayoutList());
    }

}


