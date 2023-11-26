<?php

namespace App\Helpers;

use InvalidArgumentException;

class ArrayCalculationHelper
{
    /**
     * @param array<int> $array1
     * @param array<int> $array2
     * @return array<int>
     */
     public static function vectorAddition(array $array1, array $array2): array
     {
         if (count($array1) !== count($array2)) {
             throw new InvalidArgumentException('Arrays must be of the same length');
         }

         return array_map(static fn (int $value1, int $value2): int => $value1 + $value2, $array1, $array2);
     }

    /**
     * @param array<int> $array
     * @param int $scalar
     * @return array<int>
     */
    public static function scalarMultiply(array $array, int $scalar): array
    {
        return array_map(static fn (int $value): int => $value * $scalar, $array);
    }

    /**
     * @param array<int> $array
     * @return array<non-negative-int>
     */
    public static function setNegativeValuesToZero(array $array): array
    {
        return array_map(static fn ($value): int => max(0, $value), $array);
    }
}
