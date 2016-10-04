<?php

namespace Sjoerdmaessen\MachineLearning\Dataset\Writer;

use Sjoerdmaessen\MachineLearning\Dataset\WriterAbstract;

class SVMLight extends WriterAbstract
{
    /**
     * @param $path , note that the recommended extension is .svmlight
     * @return bool
     */
    public function write($absolutePath, $filename)
    {
        $numberOfLabels = $this->getNumberOfLabels();
        $numberOfSamples = $this->getNumberOfSamples();

        // Open the file for export
        $fileResource = $this->getFileResource($absolutePath, $filename);
        
        // Start exporting the data
        for ($i = 0; $i < $numberOfSamples; $i++) {

            // Init new line
            $line = '';
            foreach ($this->samples[$i] as $key => $feature) {

                // Check if we need to append the label
                if ($key == 0 && $numberOfLabels) {
                    $line .= $this->labels[$i];
                }

                $line .= "\t" . $key . ':' . $feature;
            }

            // Write line
            fwrite($fileResource, $line . "\r\n");
        }

        return true;
    }
}