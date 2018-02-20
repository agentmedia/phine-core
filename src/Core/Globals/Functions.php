<?php
use Phine\Framework\System\Str;
use Phine\Framework\System\HtmlQuoteMode;
use Phine\Framework\System\IO\File;
use Phine\Framework\Wording\Worder;
use Phine\Framework\System\Http\Request;

/**
 * Escapes special chars
 * @param string $string The input string
 * @param HtmlQuoteMode $mode If omitted, only double quotes are encoded.
 * @return string Returns the html escaped string
 */
function Html($string, HtmlQuoteMode $mode = null)
{
    return Str::ToHtml($string, $mode);
}

/**
 * Escapes special chars and echoes the string
 * @param string $string The input string
 * @param HtmlQuoteMode $mode The Quote Mode
 */
function HtmlOut($string, HtmlQuoteMode $mode = null)
{
    echo Html($string, $mode);
}
/**
 * Returns a quoted string usable as javascipt variable
 * @param string $string
 * @param boolean $htmlEncode True if shall be hmtl encoded before
 */
function JsVarOut($string, $htmlEncode = false)
{
    echo Str::ToJavascript($string, $htmlEncode);
}
function Trans($string)
{
    $funcArgs = func_get_args();
    array_shift($funcArgs);
    return TransArgs($string, $funcArgs);
}

function TransArgs($string, array $args)
{
    return Worder::ReplaceArgs($string, $args);
}

/**
 * Checks if the translation exists
 * @param string $string
 * @return boolean Returns true if the replacement is given
 */
function TransExists($string)
{
    return Worder::HasReplacement($string);
}

/**
 * Translates and outputs the string as html
 * @param string $string The placeholder
 */
function TransOut($string)
{
    $funcArgs = func_get_args();
    array_shift($funcArgs);
    HtmlOut(TransArgs($string, $funcArgs));
}


/**
 * Requires (includes) a file at the given path once, only if it exists 
 * @param string $path The file path
 * @return boolean Returns true if the file was found
 */
function RequireOnceIfExists($path)
{
    if (File::Exists($path))
    {
        require_once $path;
    }
    return false;
}

/**
 * Requires (includes) a file at the given path, only if it exists 
 * @param string $path The file path
 * @return boolean Returns true if the file was found
 */
function RequireIfExists($path)
{
    if (File::Exists($path))
    {
        require $path;
        return true;
    }
    return false;
}
/**
 * The current uri (without base url)
 * @return string Returns the current uri
 */
function CurrentUri()
{
    return Request::Uri();
}


/**
 * The current, full url
 * @return string Returns the current url
 */
function CurrentUrl()
{
    return Request::FullUrl();
}
