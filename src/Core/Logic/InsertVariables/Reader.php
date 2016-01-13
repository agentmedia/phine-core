<?php

namespace Phine\Bundles\Core\Logic\InsertVariables;
use Phine\Framework\System\String;

class Reader
{
    const Start = '{{';
    const End = '}}';
    
    const PropertySeparator= '::';
    const ParamsStart= '(';
    const ParamsEnd = ')';
    const ParamsSeparator = ',';
    const ParamsAssign = '=';
    const FilterSeparator= '|';
    /**
     * 
     * @param string $text The text
     * @param int $startPos The start position
     * @return mixed Returns the next assumed token start position or fals if none was found
     */
    function NextTokenStart($text, $startPos)
    {
        return strpos($text, self::Start, $startPos);
    }
    /**
     * Tries to parse a token beginning on the start marker
     * @param string $text The text
     * @param int $startPos The start position of the token
     * @param int& $endPos The end position of the token or the next position
     * @return Token Returns the parsed token or null if parsing failed
     */
    public function ParseToken($text, $startPos, &$endPos)
    {
        $endPos = $startPos + strlen(self::Start); 
        $tokenString = $this->ExtractTokenString($text, $startPos, $endPos);
        
        if (!$tokenString)
        {
            return null;
        }
        $nextStop = 0;
        $type = $this->ParseType($tokenString, $nextStop);
        
        if (!$type)
        {
            return null;
        }
        $typeParams = $this->ParseParams($tokenString, $nextStop);
         
        if ($typeParams === false)
        {
            return null;
        }
        $property = $this->ParseProperty($tokenString, $nextStop);
        
        if (!$property)
        {
            return null;
        }
        $propParams = $this->ParseParams($tokenString, $nextStop);
        
        if ($propParams === false)
        {
            return null;
        }
        $filters = $this->ParseFilters($tokenString, $nextStop);
        if ($filters === false)
        {
            return null;
        }
        return new Token($type, $typeParams, $property, $propParams, $filters);
    }
    private function ExtractTokenString($text, $startPos, &$endPos)
    {
        $stringEnd = strpos($text, self::End, $startPos);
        if ($stringEnd === false)
        {
            return '';
        }
        $endPos = $stringEnd + strlen(self::End);
        $startPos += strlen(self::Start);
        return trim(substr($text, $startPos, $stringEnd - $startPos));
    }
    private function ParseParams($tokenString, &$nextStop)
    {
        $params = array();
        $trimString = trim(substr($tokenString, $nextStop));
        if (!String::StartsWith(self::ParamsStart, $trimString))
        {
            return $params;
        }
        $paramsStart = strpos($tokenString, self::ParamsStart, $nextStop) + strlen(self::ParamsStart);
        $paramsEnd= strpos($tokenString, self::ParamsEnd, $paramsStart);
        if ($paramsEnd === false)
        {
            return false;
        }
        $strParams = explode(self::ParamsSeparator, substr($tokenString, $paramsStart, $paramsEnd - $paramsStart));
        foreach ($strParams as $strParam)
        {
            $key = '';
            $value = '';
            if (!$this->ParseParam($strParam, $key, $value))
            {
                return false;
            }
            $params[$key] = $value;
        }
        $nextStop = $paramsEnd + strlen(self::ParamsEnd);
        return $params;
    }
    
     private function ParseParam($strParam, &$key, &$value)
    {
        $keyValue = explode(self::ParamsAssign, $strParam);
        if (count($keyValue) != 2)
        {
            return false;
        }
        
        $key = trim($keyValue[0]);
        $value = trim($keyValue[1]);
        
        return $key && ctype_alnum($key) && ($value == '' || ctype_alnum($value));
    }
    
    /**
     * Parses the filters
     * @return string[] Returns the filter
     */
    private function ParseFilters($tokenString, $nextStop)
    {
        $filters = array();
        if ($nextStop === false)
        {
            return $filters;
        }
        $trimString = trim(substr($tokenString, $nextStop));
        if ($trimString == '')
        {
            return $filters;
        }
        if (!String::StartsWith(self::FilterSeparator, $trimString))
        {
            return false;
        }
        $filtersStart = strpos($tokenString, self::FilterSeparator, $nextStop) + strlen(self::FilterSeparator);
        $filterNames = explode(self::FilterSeparator, substr($tokenString, $filtersStart));
        foreach ($filterNames as $filterName)
        {
            $filter = trim($filterName);
            if (!ctype_alnum($filter))
            {
                return false;
            }
            $filters[] = $filter;
        }
        return $filters;
    }
    private function ParseProperty($tokenString, &$nextStop)
    {
        $trimString = trim(substr($tokenString, $nextStop));
        if (!String::StartsWith(self::PropertySeparator, $trimString))
        {
            return false;
        }
        $propStart = strpos($tokenString, self::PropertySeparator, $nextStop) + strlen(self::PropertySeparator);
        $nextStart = strpos($tokenString, self::ParamsStart, $propStart);
        if ($nextStart === false)
        {
            $nextStart = strpos($tokenString, self::FilterSeparator, $propStart);
        }
        $propEnd = $nextStart ?: false;
        $strProp = $propEnd !== false? substr($tokenString, $propStart, $propEnd - $propStart) : substr($tokenString, $propStart);
        $prop = trim($strProp);
        $nextStop = $nextStart ?: false;
        $result = ctype_alnum($prop) ? $prop : '';
        return $result;
    }
    
   


    private static function ParseType($tokenString, &$nextStop)
    {
       
        $propStartPos = strpos($tokenString, self::PropertySeparator);
        
        if ($propStartPos === false)
        {
            return '';
        }
        $parStartPos = strpos($tokenString, self::ParamsStart);
        $nextStop = $parStartPos !== false ? min($parStartPos, $propStartPos) : $propStartPos;
        
        $result = trim(substr($tokenString, 0, $nextStop));
       
        return ctype_alnum($result) ? $result : '';
    }
}

