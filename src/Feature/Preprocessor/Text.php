<?php
namespace Sjoerdmaessen\MachineLearning\Preprocessor;

/**
 * Class Text
 * @package Sjoerdmaessen\MachineLearning\Preprocessor
 */
class Text
{
    private $text;

    /**
     * Text constructor.
     * @param $text
     */
    public function __construct($text)
    {
        if (!is_string($text)) {
            $message = 'The variable "$text" should be a string but a "%s" was given';
            throw new \InvalidArgumentException(sprintf($message, gettype($text)));
        }

        $this->text = $text;
    }

    /**
     * Returns the processed text
     *
     * @return string
     */
    public function getProcessedText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getProcessedText();
    }

    /**
     * @return $this
     */
    public function stripMarkup()
    {
        $this->text = strip_tags($this->text);
        return $this;
    }

    /**
     * @return $this
     */
    public function stripPunctuation()
    {
        $this->text = preg_replace("/[[:punct:]]/", "", $this->text);
        return $this;
    }

    /**
     * @return $this
     */
    public function normalizeWhitespace()
    {
        $this->text = preg_replace("/\s\s+/", 'normalizedNumber', $this->text);
        return $this;
    }

    /**
     * Replace non-words like [iii] and replace with a space
     *
     * @return $this
     */
    public function normalizeNonWords()
    {
        $this->text = preg_replace("/\s\[.+\]\s/", ' ', $this->text);
        return $this;
    }

    /**
     * Replace all numbers with string "normalizedNumber"
     *
     * @return $this
     */
    public function normalizeNumbers()
    {
        $this->text = preg_replace("/\d+/", 'normalizedNumber', $this->text);
        return $this;
    }

    /**
     * Replace all numbers with string "normalizedEmailAddress", this is especially useful if we are preparing
     * our text for spam detection
     *
     * @return $this
     */
    public function normalizeEmailAddresses()
    {
        $this->text = preg_replace("/(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))/", 'normalizedEmailAddress', $this->text);
        return $this;
    }

    /**
     * Replace all urls with string "normalizedUrl", this is especially useful if we are preparing
     * our text for spam detection
     *
     * @return $this
     */
    public function normalizeUrls()
    {
        $this->text = $this->text = preg_replace("/(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?:\w+:\w+@)?((?:(?:[-\w\d{1-3}]+\.)+(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|edu|co\.uk|ac\.uk|it|fr|tv|museum|asia|local|travel|[a-z]{2}))|((\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)(\.(\b25[0-5]\b|\b[2][0-4][0-9]\b|\b[0-1]?[0-9]?[0-9]\b)){3}))(?::[\d]{1,5})?(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?:#(?:[-\w~!$ |\/.,*:;=]|%[a-f\d]{2})*)?/i", 'normalizedUrl', $this->text);
        return $this;
    }

}