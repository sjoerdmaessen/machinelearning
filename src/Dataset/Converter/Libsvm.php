<?php
namespace Sjoerdmaessen\MachineLearning\Dataset\Converter;

class Libsvm
{
    /**
     * @var array
     */
    protected $dataset;
    /**
     * @var bool
     */
    private $labelsAreIncluded;

    /**
     * @param array $dataset
     * @param bool $labelsAreIncluded
     */
    public function __construct(array $dataset, $labelsAreIncluded = false)
    {
        $this->dataset = $dataset;
        $this->labelsAreIncluded = $labelsAreIncluded;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAsString();
    }

    /**
     * Convert to libsvm format
     *
     * @return string
     */
    public function getAsString()
    {
        $output = '';
        foreach ($this->dataset as $item) {
            $line = '';
            foreach ($item as $key => $value) {
                if ($this->labelsAreIncluded && $key === 0) {
                    $line .= $value;
                } else {
                    $line .= "\t" . $key . ':' . $value;
                }
            }
            $output .= $line . "\r\n";
        }

        return $output;
    }

}