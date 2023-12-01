<?php

require_once(__DIR__ . '/objects/Goal.php');
trait GoalTrait {

    /** Choose the goals used for the entire game, at the beginning of it, according to level options. */
    public function selectGoals() {
        $goals = [];
        foreach (GOAL_COLORS as $color) {
            $matchingGoals = array_values(array_filter($this->GOALS, fn ($g) => $g->color === $color));
            $level  = intval($this->getGameStateValue(GOAL_LEVEL));
            if ($level != LEVEL_RANDOM) {
                $matchingGoals = array_values(array_filter($matchingGoals, fn ($g) => $g->level === $level));
            }
            $randIndex = bga_rand(0, count($matchingGoals) - 1);
            $goals[] = $matchingGoals[$randIndex];
        }
        $this->setGlobalVariable("GOALS", $goals);
    }

    /** Returns only goals that applies to the current round. */
    public function getRoundGoals() {
        $round = intval(self::getGameStateValue(ROUND));
        $goals = $this->getGlobalVariable("GOALS", true);
        switch ($round) {
            case 1:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_GREEN)));
            case 2:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_GREEN)));
            case 3:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_RED)));
            case 4:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_GREEN || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED)));
            case 5:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED)));

            default:
                throw new BgaVisibleSystemException("this round is never supposed to happen : " . $round);
        }
    }

    /** Calculates points for a given goal and a given player. Called at the end of each round. */
    public function calculateGoalPoints(Goal̤ $goal, $playerId) {
        $grid = $this->getGrid($playerId);
        switch ($goal->id) {
            case 1:
                return $this->calculateGoalAnimalTouchingAtLeastOneKindOfElement($grid, $playerId, ANIMAL_CROCODILE, ANIMAL_GIRAFFE, null);
            case 2:
                return $this->calculateGoalAnimalTouchingAtLeastOneKindOfElement($grid, $playerId, ANIMAL_GORILLA, null, LAND_WATER);
            default:
                # code...
                break;
        }
        return 0;
    }

    function getGrid($playerId) {
        $grid = [];
        $fakeBiome = new Biome(0, -1, 0); //to avoid null errors
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $grid[] = array_fill(0, GRID_SIZE, $fakeBiome);
        }

        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("grid$playerId", null, "card_order_in_grid"));
        //row first, then col
        foreach ($cards as $card) {
            $biomes = $this->getRotatedBiomes($card);
            $anchorCol = $card->location_arg % GRID_SIZE - 1; //array starting at 0
            $anchorRow = ceil($card->location_arg / GRID_SIZE) - 1; //same
            //anchor is top left part of the card
            $grid[$anchorRow][$anchorCol] = $biomes[0];
            $grid[$anchorRow][$anchorCol + 1] = $biomes[1];
            $grid[$anchorRow + 1][$anchorCol] = $biomes[2];
            $grid[$anchorRow + 1][$anchorCol + 1] = $biomes[3];
        }
        $this->displayGrid($grid);
        return $grid;
    }

    function displayGrid(array $grid) {
        $h = count($grid);
        $w = count($grid[0]);
        $str = "grid  $w x $h|\n";
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $row = '';
            for ($j = 0; $j < GRID_SIZE - 1; $j++) {
                if (isset($grid[$i][$j])) {
                    $val = $grid[$i][$j]->animal;
                } else {
                    $val = '0';
                }
                $row .= $val;
            }
            $str .= $row . "\n";
        }
        self::dump('*******************grid', $str);
    }

    function getRotatedBiomes(BiomeCard $card) {
        switch ($card->rotation) {
            case 0:
                return $card->biomes;
            case 90:
                return [$card->biomes[2], $card->biomes[0], $card->biomes[3], $card->biomes[1]];
            case 180:
                return [$card->biomes[3], $card->biomes[2], $card->biomes[1], $card->biomes[0]];
            case 270:
                return [$card->biomes[1], $card->biomes[3], $card->biomes[0], $card->biomes[2]];
        }
    }

    function calculateGoalAnimalTouchingAtLeastOneKindOfElement(array $grid, int $animal, int $otherAnimal, int $landType = 0) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains an expected animal
                if ($grid[$row][$col]->animal == $animal) {
                    // Check adjacent cells (orthogonally)
                    if (
                        $otherAnimal && $row > 0 && $grid[$row - 1][$col]->animal == $otherAnimal ||
                        $otherAnimal && $row < $gridSize - 1 && $grid[$row + 1][$col]->animal == $otherAnimal
                        || $otherAnimal && $col > 0 && $grid[$row][$col - 1]->animal == $otherAnimal
                        || $otherAnimal && $col < count($grid[$row]) - 1 && $grid[$row][$col + 1]->animal == $otherAnimal
                    ) {
                        $points += 2;
                    }
                }
            }
        }

        return $points;
    }
}
