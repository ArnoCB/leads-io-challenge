<?php

namespace App\Console\Commands;

use App\Services\CookieRecipeOptimizationService;
use Illuminate\Console\Command;
use JsonException;

class CookieRecipeCommand extends Command
{
    protected $signature = 'cookie:recipe:optimize';

    protected $description = 'This command gives the solutions for the cookie recipe optimization problem';

    /**
     * @throws JsonException
     */
    public function handle(CookieRecipeOptimizationService $service): void
    {
        $bestRecipe = json_encode($service->getBestCookieRecipe(), JSON_THROW_ON_ERROR);
        $bestScore  = $service->getBestScore();

        $this->info("The best recipe is: $bestRecipe");
        $this->info("The best score is: $bestScore");

        $service = new CookieRecipeOptimizationService(calories: 500);
        $bestRecipe = json_encode($service->getBestCookieRecipe(), JSON_THROW_ON_ERROR);
        $bestScore  = $service->getBestScore();

        $this->info("The best recipe for 500 calories is: $bestRecipe");
        $this->info("The best score for 500 calories is: $bestScore");
    }
}
