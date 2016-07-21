<?php
namespace Sjoerdmaessen\MachineLearning\Feature\Preprocessor;

/**
 * Class Scale
 */
class Scaler
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
    public function rescale($input, $currentMin = 0, $currentMax = 100, $newMin = 0, $newMax = 1)
    {
        if(is_bool($input)) {
            return $input ? $newMax : $newMin;
        }

        $currentRange = ($currentMax - $currentMin);
        $newRange = ($newMax - $newMin);

        if (is_array($input)) {
            foreach ($input as &$value) {
                $value = $newMin + $newRange * (($value - $currentMin) / $currentRange);
            }
        } else {
            $input = $newMin + $newRange * (($input - $currentMin) / $currentRange);
        }

        return $input;
    }

    /**
     * Returns the percentage difference between two values
     *
     * @param $value1
     * @param $value2
     * @return float|int
     */
    public function diff($value1, $value2)
    {
        if($value2 == 0) {
            $percentage = 100;
        } else {
            $percentage = ($value1 - $value2) / $value2 * 100;
        }

        return $percentage;
    }

}