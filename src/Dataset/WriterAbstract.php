<?php

namespace MLSjoerdmaessen\MachineLearning\Dataset;

abstract class WriterAbstract
{
    protected $labels;
    protected $samples;
    protected $numberOfLabels;
    protected $numberOfSamples;

    /**
     * ExportLabelsAndSamples constructor.
     * @param $samples
     * @param $labels
     */
    public function __construct(array $samples, array $labels = null)
    {
        $this->samples = $samples;
        $this->labels = $labels;

        $this->numberOfLabels = count($labels);
        $this->numberOfSamples = count($samples);

        // Validate
        if($labels && $this->getNumberOfLabels() != $this->getNumberOfSamples()) {
            throw new \InvalidArgumentException(sprintf('The amount of labels (%d) must match the amount of samples (%d)', $this->getNumberOfLabels(), $this->getNumberOfSamples()));
        }
    }

    /**
     * @return int
     */
    protected function getNumberOfLabels()
    {
        return $this->numberOfLabels;
    }

    /**
     * @return int
     */
    protected function getNumberOfSamples()
    {
        return $this->numberOfSamples;
    }

    /**
     * @param $absolutePath
     * @param $filename
     * @return resource
     */
    protected function getFileResource($absolutePath, $filename)
    {
        if(!is_writable($absolutePath)) {
            throw new \RuntimeException(sprintf('The path "%s" is not writable!', $absolutePath));
        }
        $fullPath = $absolutePath . '/' . $filename;

        return fopen($fullPath, 'w');
    }

    /**
     * @param $absolutePath
     * @param $filename
     * @return mixed
     */
    abstract public function write($absolutePath, $filename);

}