<?php
namespace Sjoerdmaessen\MachineLearning\Feature\Extraction;

/**
 * Class Text
 */
class Text
{
    private $text;
    protected $wordList;

    /**
     * Text constructor.
     * @param $text , should be sanitized and preprocessed (converted to lowercase if needed for example)
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
     * Returns ASCII
     *
     * @return array
     */
    public function getCharFrequencies()
    {
        $text = preg_replace("/[^\p{L}]/u", '', $this->text);

        $total = strlen($text);
        $charFreqquencies = count_chars($text);

        // Convert to percentages in range 0-1
        array_walk(
            $charFreqquencies,
            function (&$item, $key, $total) {
                $item = $item / $total;
            },
            $total
        );

        return $charFreqquencies;
    }

    /**
     * Returns the average word length of a string
     *
     * @return float
     */
    public function getAverageTermLength()
    {
        $wordsLength = array_map('strlen', $this->getTermList());
        return array_sum($wordsLength) / count($wordsLength);
    }

    /**
     * Also called count vectorizer
     *
     * @param array $termsToIgnore , fe: a list with stopwords ['a', 'an', 'and', 'the', 'this', 'or', 'is'].
     * @param null $termWhitelist
     * @param null $limit
     * @return array
     */
    public function getTermFrequencies($termsToIgnore = [], $termWhitelist = null, $limit = null)
    {
        $terms = $this->getTermList();

        // Flip stopwords for faster lookup
        if (!empty($termsToIgnore)) {
            $stopWordsLookup = array_flip($termsToIgnore);
            $terms = array_filter(
                $terms, function ($term) use ($stopWordsLookup) {
                return !array_key_exists($term, $stopWordsLookup);
            }
            );
        }

        // Check if we have a whitelist
        if (is_array($termWhitelist)) {
            // Filter terms against the whitelist
            $termWhitelistLookup = array_flip($termWhitelist);
            $terms = array_filter(
                $terms, function ($term) use ($termWhitelistLookup) {
                return array_key_exists($term, $termWhitelistLookup);
            }
            );
        }

        // Count and sort desc
        $termFrequencies = array_count_values($terms);
        arsort($termFrequencies);

        // Convert to percentages in range 0-1
        $numberOfTerms = array_sum($termFrequencies);
        foreach ($termFrequencies as $term => $occurences) {
            $termFrequencies[$term] = $occurences / $numberOfTerms;
        }

        // Return subset
        if ($limit) {
            $termFrequencies = array_slice($termFrequencies, 0, $limit);
        }

        return $termFrequencies;
    }

    /**
     * @return array
     */
    protected function getTermList()
    {
        if (!is_array($this->wordList)) {
            $this->wordList = preg_split('/\W/', $this->text, 0, PREG_SPLIT_NO_EMPTY);
        }

        return $this->wordList;
    }

}