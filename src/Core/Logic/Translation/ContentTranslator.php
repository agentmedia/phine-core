<?php

namespace Phine\Bundles\Core\Logic\Translation;
use Phine\Framework\Localization\Base\FormatTranslator;
use Phine\Framework\Localization\PhpTranslator;
use Phine\Database\Core\Content;
use Phine\Database\Core\ContentWording;
use Phine\Framework\System\String;

/**
 * Translator for frontend content labels
 */
class ContentTranslator extends FormatTranslator
{
    /**
     * The php translator
     * @var PhpTranslator
     */
    private $phpTranslator;
    
    /**
     * The singleton instance of the content translator
     * ContentTranslator
     */
    private static $singleton;
    
    /**
     * The content wording texts as key value pairs
     * @var string[]
     */
    private $texts = array();
    
    /**
     * Set the current content to retrieve customized wording texts
     * @param Content $content The content
     */
    public function SetContent(Content $content)
    {
        $this->texts = array();
        $wordings = ContentWording::Schema()->FetchByContent(false, $content);
        foreach($wordings as $wording)
        {
            $prefix = String::Replace('-', '.', $content->GetType());
            $this->texts[$prefix . '.' . $wording->GetPlaceholder()] = $wording->GetText();
        }
    }
    
    /**
     * The content translator as singleton object
     * @return ContentTranslator
     */
    static function Singleton()
    {
        if (!self::$singleton)
        {
            self::$singleton = new self();
        }
        return self::$singleton;
    }
    /**
     * Creates the content translator and initializes The internal php translator
     */
    private function __construct()
    {
        $this->phpTranslator = PhpTranslator::Singleton();
    }
    
    /**
     * Gets the placeholder replacement text
     * @param string $placeholder The placeholder
     * @return string Returns the replacement, without parameters attached, yet
     */
    public function GetReplacement($placeholder)
    {
        if (isset($this->texts[$placeholder]))
        {
            return $this->texts[$placeholder];
        }
        return $this->phpTranslator->GetReplacement($placeholder);
    }

    /**
     * 
     * @return string
     */
    public function GetLanguage()
    {
        return $this->phpTranslator->GetLanguage();
    }

    /**
     * Sets the language
     * @param string $language
     */
    public function SetLanguage($language)
    {
        $this->phpTranslator->SetLanguage($language);
        $this->language = $language;
    }
}


