<?php
namespace Sjoerdmaessen\MachineLearning\Feature\Preprocessor;

/**
 * Class Scale
 * @package Sjoerdmaessen\MachineLearning\Preprocessor
 */
class Scale
{
    /**
     * Rescales a number from one scale to a number on another scale
     *
     * @param array|float|int $input
     * @param int $currentMin
     * @param int $currentMax
     * @param int $newMin
     * @param int $newMax
     */
    static public function rescale($input, $currentMin = 0, $currentMax = 100, $newMin = 0, $newMax = 1)
    {
        if (is_array($input)) {
            foreach ($input as &$value) {
                $value = $newMin + ($newMax - $newMin) * (($value - $currentMin) / ($currentMax - $currentMin));
            }
        } else {
            $input = $newMin + ($newMax - $newMin) * (($input - $currentMin) / ($currentMax - $currentMin));
        }

        return $input;
    }

}