<?php
namespace Phine\Bundles\Core\Logic\Util;
use Phine\Framework\System\Str;

/**
 * Utility class to serialize/deserialize array in text areas
 */
class ArrayLinesSerializer
{
    private $separator;
    function __construct($keyValueSeparator = '=')
    {
        $this->separator = $keyValueSeparator;
    }
    
    /**
     * 
     * @param array $values The values as key value pair
     * @param type $separator
     * @return string Returns the array serialized to multiline string
     */
    function ArrayToLines(array $values)
    {
        $lines = array();
        foreach ($values as $key=>$value)
        {
            $lines[] = $key . $this->separator  . $value;
        }
        return join("\r\n", $lines);
    }
    /**
     * Fetches the lines of a text and serializes them to an array of key value pairs
     * @param string $text The text representation of the array
     * @return array Returns the array representation as associative array
     */
    function LinesToArray($text)
    {
        $result = array();
        $lines = Str::SplitLines($text);
        foreach ($lines as $line)
        {
            $line = trim($line);
            if (!$line)
            {
                continue;
            }
            $pos = strpos($line, $this->separator);
            $key = $line;
            $value = '';
            if ($pos !== false)
            {
                $key = substr($line, 0, $pos);
                $value = substr($line, $pos + strlen($this->separator));
            }
            $result[trim($key)] = trim($value);
        }
        return $result;
    }
}
