<?php

namespace  Tests\Unit\Services;

use App\Services\CookieRecipeOptimizationService;
use Tests\TestCase;

class CookieRecipeOptimizationServiceTest extends TestCase
{
    public const INGREDIENTS_EXAMPLE = [
        'butterscotch' => [
            'capacity'   => -1,
            'durability' => -2,
            'flavor'     => 6,
            'texture'    => 3,
            'calories'   => 8
        ],
        'cinnamon'     => [
            'capacity'   => 2,
            'durability' => 3,
            'flavor'     => -2,
            'texture'    => -1,
            'calories'   => 3
        ]
    ];

    public const BEST_RECIPE_EXAMPLE = [
        'butterscotch' => 44,
        'cinnamon'     => 56,
    ];

    public const BEST_SCORE_EXAMPLE = 62842880;

    public const BEST_RECIPE_FOR_500_CALORIES = [
        'butterscotch' => 40,
        'cinnamon'     => 60,
    ];

    public const BEST_SCORE_FOR_500_CALORIES = 57600000;

    public function testBestRecipe(): void
    {
        $cookieRecipeService = new CookieRecipeOptimizationService(self::INGREDIENTS_EXAMPLE);

        $this->assertEquals(self::BEST_RECIPE_EXAMPLE, $cookieRecipeService->getBestCookieRecipe());
        $this->assertEquals(self::BEST_SCORE_EXAMPLE, $cookieRecipeService->getBestScore());
    }

    public function testBestRecipeFor500Calories(): void
    {
        $cookieRecipeService = new CookieRecipeOptimizationService(self::INGREDIENTS_EXAMPLE, 500);
        $this->assertEquals(self::BEST_RECIPE_FOR_500_CALORIES, $cookieRecipeService->getBestCookieRecipe());
        $this->assertEquals(self::BEST_SCORE_FOR_500_CALORIES, $cookieRecipeService->getBestScore());
    }

    public function testBestRecipeForCaloriesWithNoSolutionGivesSensibleAnswer(): void
    {
        $cookieRecipeService = new CookieRecipeOptimizationService(self::INGREDIENTS_EXAMPLE, 5000);
        $this->assertEquals([], $cookieRecipeService->getBestCookieRecipe());
        $this->assertEquals(0, $cookieRecipeService->getBestScore());
    }

    public function testAnswerForPart1(): void
    {
        $cookieRecipeService = new CookieRecipeOptimizationService();
        $this->assertEquals(
            ['sprinkles' => 17, 'butterscotch' => 19, 'chocolate' => 38, 'candy' => 26],
            $cookieRecipeService->getBestCookieRecipe(),
        );

        $this->assertEquals(21367368, $cookieRecipeService->getBestScore());
    }

    public function testAnswerForPart2(): void
    {
        $cookieRecipeService = new CookieRecipeOptimizationService(calories: 500);
        $this->assertEquals(
            ['sprinkles' => 46, 'butterscotch' => 14, 'chocolate' => 30, 'candy' => 10],
            $cookieRecipeService->getBestCookieRecipe());

        $this->assertEquals(1766400, $cookieRecipeService->getBestScore());
    }
}
