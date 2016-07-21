<?php
namespace Sjoerdmaessen\MachineLearning\Dataset\Validator;

use Sjoerdmaessen\MachineLearning\Dataset\ValidatorAbstract;

class SVMLight extends ValidatorAbstract
{
    /**
     * Main check method
     *
     * @param string $pathToFile
     * @throws \RuntimeException if file not found
     * @return bool
     */
    public function validate($pathToFile)
    {
        $this->clearErrors();

        $path = new \SplFileInfo($pathToFile);
        if (!$path->isFile() || !$path->isReadable()) {
            throw new \RuntimeException(sprintf('File "%s" not found', $pathToFile));
        }
        $file = $path->openFile('r');

        $currentLineNumber = 1;
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($line == '' && $file->eof()) {
                break;
            }

            // Validate line structure
            $this->validateEOL($currentLineNumber, $line);

            $nodes = array_filter(preg_split('/\s+/', $line));

            // Validate label
            $label = array_shift($nodes);
            $this->validateLabel($currentLineNumber, $label);

            // Validate features format
            $this->validateFeatures($currentLineNumber, $nodes);

            // Increate the line number
            $currentLineNumber++;
        }

        return $this->isValid();
    }

    /**
     * @param $lineNumber
     * @param $line
     * @return void
     */
    protected function validateEOL($lineNumber, $line)
    {
        if (substr($line, -1) != "\n") {
            $this->addError($lineNumber, 'missing a newline character in the end');
        }
    }

    /**
     * @param $lineNumber
     * @param $features
     * @return bool
     */
    protected function validateFeatures($lineNumber, $features)
    {
        $prevIndex = -1;
        $prevFeature = null;
        foreach ($features as $feature) {
            $labelAndValue = explode(':', $feature);
            if (count($labelAndValue) != 2 || $this->isFloat($labelAndValue[1])) {
                $index = (int) $labelAndValue[0];
                if ($index < 0) {
                    $this->addError($lineNumber, "feature index must be positive; wrong feature {$feature}");
                } elseif ($index <= $prevIndex) {
                    $this->addError($lineNumber, "feature indices must be in an ascending order, previous/current features {$prevFeature} {$feature}");
                }
                $prevIndex = $index;
                $prevFeature = $feature;
            } else {
                $this->addError($lineNumber, "feature '{$feature}' not an <index>:<value> pair, <index> integer, <value> real number ");
            }
        }
    }

    /**
     * @param $lineNumber
     * @param $label
     * @return void
     */
    protected function validateLabel($lineNumber, $label)
    {
        if (false !== strpos($label, ',')) {
            if (!$this->areFloats(explode(',', $label))) {
                $this->addError($lineNumber, "label {$label} is not a valid multi-label form");
            }
        } else {
            if (!$this->isFloat($label)) {
                $this->addError($lineNumber, "label {$label} is not a number");
            }
        }
    }

    /**
     * Validate float number
     *
     * @param string $string
     * @return bool
     */
    protected function isFloat($string)
    {
        $float = (float) $string;
        return !is_nan($float) && !is_infinite($float);
    }

    /**
     * Validate array of float numbers
     *
     * @param array $strings
     * @return bool
     */
    protected function areFloats(array $strings)
    {
        foreach ($strings as $string) {
            if (!$this->isFloat($string)) {
                return false;
            }
        }
        return true;
    }

}