<?php
namespace Phine\Bundles\Core\Logic\InsertVariables;
/**
 * Represents a token
 */
class Token
{
    /**
     * The object type the variable relate to
     * @var string
     */
    public $Type;
    
    /**
     * The parameters of the object that is requested
     * @var string[]
     */
    public $TypeParams;
    
    /**
     * The requested property
     * @var string
     */
    public $Property;
    
    /**
     * The parameters of the property requested
     * @var string[]
     */
    public $PropertyParams;
    
    /**
     * A list of filter functions to apply to the result
     * @var string[]
     */
    public $Filters;
    
    /**
     * Creates a new token
     * @param string $type
     * @param array $typeParams
     * @param string $property
     * @param array $propertyParams
     * @param array $filters
     */
    function __construct($type, array $typeParams, $property, array $propertyParams, array $filters)
    {
        $this->Type = $type;
        $this->TypeParams = $typeParams;
        $this->Property = $property;
        $this->PropertyParams = $propertyParams;
        $this->Filters = $filters;
    }
    
    public function TypeParam($name)
    {
        return isset($this->TypeParams[$name]) ? $this->TypeParams[$name] : null; 
    }
    
    public function PropertyParam($name)
    {
        return isset($this->PropertyParams[$name]) ? $this->PropertyParams[$name] : null; 
    }
}
