<?php

namespace Phine\Bundles\Core\Logic\Module;
/**
 * A Json module (mainly for the backend)
 */
class JsonModule extends ModuleBase
{
    /**
     *
     * @var \stdClass;
     */
    protected $result;
    /**
     * Creates the json module and initializes the result object
     */
    final function __construct()
    {
        $this->result = new \stdClass();
        $this->result->success = true;
        $this->result->message = '';
        
    }
    protected final function AttachSuccess($message = '')
    {
        $this->result->message = $message;
        $this->result->success = true;
    }
    
    /**
     * Attaches the exception by setting message to the result and the success flag to false
     * @param string $message
     */
    protected final function AttachError($message)
    {
        $this->result->message = $message;
        $this->result->success = false;
    }
    
    /**
     * Attaches the exception by setting its message to the result and the success flag to false
     * @param \Exception $exc
     */
    protected final function AttachException(\Exception  $exc)
    {
        $this->AttachError($exc->getMessage());
    }

    /**
     * Gathers the output by encoding the result to json
     */
    protected final function GatherOutput()
    {
        try
        {
            $this->output = json_encode($this->result);
        }
        catch (\Exception $ex)
        {
            $this->AttachException($ex);
        }
    }

}

