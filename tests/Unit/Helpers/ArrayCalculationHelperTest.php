<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ArrayCalculationHelper;
use Tests\TestCase;

class ArrayCalculationHelperTest extends TestCase
{
    public function testVectorAddition(): void
    {
        $array1 = [1, 2, 3];
        $array2 = [4, 5, 6];

        $this->assertEquals([5, 7, 9], ArrayCalculationHelper::vectorAddition($array1, $array2));
    }

    public function testVectorAdditionWithEmptyArray(): void
    {
        $array1 = [1, 2, 3];
        $array2 = [];

        $this->assertEquals([1, 2, 3], ArrayCalculationHelper::vectorAddition($array1, $array2));
    }

    public function testScalarMultiply(): void
    {
        $array = [1, 2, 3];
        $scalar = 2;

        $this->assertEquals([2, 4, 6], ArrayCalculationHelper::scalarMultiply($array, $scalar));
    }

    public function testSetNegativeValuesToZero(): void
    {
        $array = [1, -2, 3, -4];

        $this->assertEquals([1, 0, 3, 0], ArrayCalculationHelper::setNegativeValuesToZero($array));
    }
}
