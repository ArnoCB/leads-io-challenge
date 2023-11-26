<?php

 namespace App\Services;

use App\Helpers\ArrayCalculationHelper;

class CookieRecipeOptimizationService
{
    /**
     * @var array<string, array<string, int>>
     */
    public const INGREDIENTS = [
        'sprinkles' => [
            'capacity' => 2,
            'durability' => 0,
            'flavor' => -2,
            'texture' => 0,
            'calories' => 3
        ],
        'butterscotch' => [
            'capacity' => 0,
            'durability' => 5,
            'flavor' => -3,
            'texture' => 0,
            'calories' => 3
        ],
        'chocolate' => [
            'capacity' => 0,
            'durability' => 0,
            'flavor' => 5,
            'texture' => -1,
            'calories' => 8
        ],
        'candy' => [
            'capacity' => 0,
            'durability' => -1,
            'flavor' => 0,
            'texture' => 5,
            'calories' => 8
        ],
    ];

    public const MAX_TEA_SPOONS = 100;

    /**
     * @var ?array<string, int>
     */
    private ?array $bestRecipe = null;

    private ?int $bestScore = null;

    /**
     * @param array<string, array<string, int>> $ingredients
     * @param ?int $calories
     */
    public function __construct(
        private readonly array $ingredients = self::INGREDIENTS,
        private readonly ?int $calories = null
    ) {
        // only property promotion
    }

    /**
     * @return array<string, int>
     */
    public function getBestCookieRecipe(): array
    {
        if ($this->bestRecipe === null) {
            [$this->bestRecipe, $this->bestScore] = $this->findBestRecipe();
        }

        return $this->bestRecipe;
    }

    public function getBestScore(): int
    {
        if ($this->bestScore === null) {
            [$this->bestRecipe, $this->bestScore] = $this->findBestRecipe();
        }

        return $this->bestScore;
    }

    /**
     * Return the best recipe and its score
     *
     * @return array{0: array<string, int>, 1: int}
     */
    private function findBestRecipe(): array
    {
        return $this->findScore(
            array_keys($this->ingredients),
            self::MAX_TEA_SPOONS,
            $this->calories
        );
    }

    /**
     * This function is a recursive function that will find the best recipe. It is
     * works with any number of ingredients, i.e. the example and the problem set.
     *
     * @param array<string> $ingredients
     * @param int $maxTeaSpoons
     * @param ?int $amountOfCalories
     * @param int $bestScore
     * @param array<string, int> $bestRecipe
     * @param array<string, int> $currentRecipe
     * @return array{0: array<string, int>, 1: int}
     */
    private function findScore(
        array $ingredients,
        int   $maxTeaSpoons,
        int   $amountOfCalories = null,
        int   $bestScore = 0,
        array $bestRecipe = [],
        array $currentRecipe = [],
    ): array {
        $currentIngredient = array_shift($ingredients);

        // We can create a complete recipe, so we can return the score
        if ($ingredients === []) {
            $currentRecipe[$currentIngredient] = $maxTeaSpoons;
            $score = $this->calculateScore($currentRecipe, $amountOfCalories);

            return $score > $bestScore
                ? [$currentRecipe, $score]
                : [$bestRecipe, $bestScore];
        }

        // We have an incomplete recipe, so we need to add more ingredients
        for ($teaSpoons = 0; $teaSpoons <= $maxTeaSpoons; $teaSpoons++) {
            $currentRecipe[$currentIngredient] = $teaSpoons;

            [$bestRecipe, $bestScore] = $this->findScore(
                $ingredients,
                $maxTeaSpoons - $teaSpoons,
                $amountOfCalories,
                $bestScore,
                $bestRecipe,
                $currentRecipe,
            );
        }

        return [$bestRecipe, $bestScore];
    }

    /**
     * @param array<string, int> $recipe
     * @param ?int $amountOfCalories
     * @return int
     */
    private function calculateScore(array $recipe, ?int $amountOfCalories = null): int
    {
        if ($amountOfCalories !== null && $this->calculateAmountOfCalories($recipe) !== $amountOfCalories) {
            return 0;
        }

        // This typecast is not really necessary, but it makes the type saner
        return (int) array_product($this->calculateIngredientScores($recipe));
    }

    /**
     * @param array<string, int> $recipe
     * @return int
     */
    private function calculateAmountOfCalories(array $recipe): int
    {
        $calories = 0;

        foreach ($recipe as $ingredient => $amount) {
            $calories += $this->ingredients[$ingredient]['calories'] * $amount;
        }

        return $calories;
    }

    /**
     * Calculate the score for each ingredient of the recipe
     *
     * @param array<string, int> $recipe
     * @return array<non-negative-int>
     */
    private function calculateIngredientScores(array $recipe): array
    {
        $ingredientScores = [];

        foreach ($this->ingredients as $ingredient => $properties) {
            unset($properties['calories']);

            $step1 = ArrayCalculationHelper::scalarMultiply($properties, $recipe[$ingredient]);

            $ingredientScores = $ingredientScores !== []
                ? ArrayCalculationHelper::vectorAddition($step1, $ingredientScores)
                : $step1;
        }

        return ArrayCalculationHelper::setNegativeValuesToZero($ingredientScores);
    }
}
