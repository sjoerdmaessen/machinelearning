<?php
namespace Sjoerdmaessen\MachineLearning\Preprocessor;

/**
 * Class Scale
 * @package Sjoerdmaessen\MachineLearning\Preprocessor
 */
class Scale
{
    /**
     * Rescales a number from one scale to a number on another scale
     */
    static public function rescale($value, $currentMin = 0, $currentMax = 100, $newMin = 0, $newMax = 1)
    {
        return $newMin + ($newMax - $newMin) * (($value - $currentMin) / ($currentMax - $currentMin));
    }

}