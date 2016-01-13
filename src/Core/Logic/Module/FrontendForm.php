<?php
namespace Phine\Bundles\Core\Logic\Module;
use Phine\Bundles\Core\Logic\Module\Traits;
abstract class FrontendForm extends FrontendModule
{
    use Traits\Form;
    
    /**
     * Adds a submit button with a unique name
     * @param string $label The wording label used for the submit button
     */
    protected function AddUniqueSubmit($label)
    {
        $name = $label . '-' . $this->Content()->GetID();
        $fullLabel = $this->Label($label);
        $this->AddSubmit($name, Trans($fullLabel));
    }
}

