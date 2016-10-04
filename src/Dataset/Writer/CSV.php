<?php

namespace Sjoerdmaessen\MachineLearning\Dataset\Writer;

use Sjoerdmaessen\MachineLearning\Dataset\WriterAbstract;

class CSV extends WriterAbstract
{
    /**
     * @var array
     */
    private $headerColumns;

    /**
     * ExportLabelsAndSamples constructor.
     * @param array $samples
     * @param array $labels
     * @param array $headerColumns
     */
    public function __construct(array $samples, array $labels = null, array $headerColumns = null)
    {
        parent::__construct($samples, $labels);

        $this->headerColumns = $headerColumns;
    }

    /**
     * @param $path , note that the recommended extension is .nh.csv or .csv depending on the fact if you have headers
     * @return bool
     */
    public function write($absolutePath, $filename)
    {
        $numberOfLabels = $this->getNumberOfLabels();
        $numberOfSamples = $this->getNumberOfSamples();

        // Open the file for export
        $fileResource = $this->getFileResource($absolutePath, $filename);

        // Do we have an header?
        if($this->headerColumns) {
            fputcsv($fileResource, $this->headerColumns);
        }

        // Start exporting the data
        for ($i = 0; $i < $numberOfSamples; $i++) {

            $line = [];

            // Check if we need to append the label
            if ($numberOfLabels) {
                $line[] = $this->labels[$i];
            }

            // Add features all the feature from the sample
            foreach ($this->samples[$i] as $feature) {
                $line[] = $feature;
            }

            // Write line
            fputcsv($fileResource, $line);
        }

        return true;
    }
}