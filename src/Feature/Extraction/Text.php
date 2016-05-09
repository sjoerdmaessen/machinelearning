<?php
namespace Sjoerdmaessen\MachineLearning\Feature\Extraction;

/**
 * Class Text
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
     * Returns ASCII
     *
     * @return array
     */
    public function getCharFrequencies()
    {
        $text = preg_replace("/[^\p{L}]/u", '', $this->text);

        $total = strlen($text);
        $data = count_chars($text);

        // Convert to averages
        array_walk(
            $data,
            function (&$item, $key, $total) {
                $item = round($item / $total, 3);
            },
            $total
        );

        return $data;
    }

}