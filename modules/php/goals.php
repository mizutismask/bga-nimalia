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
                return array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_GREEN));
            case 2:
                return array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_GREEN));
            case 3:
                return array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_RED));
            case 4:
                return array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_GREEN || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED));
            case 5:
                return array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED));

            default:
                throw new BgaVisibleSystemException("this round is never supposed to happen : " . $round);
        }
    }

    /** Calculates points for a given goal and a given player. Called at the end of each round. */
    public function calculateGoalPoints(Goal̤ $goal, $playerId) {
        $grid = $this->getGrid($playerId);
        switch ($goal->id) {
            case 1:
                return $this->calculateGoalAnimalTouchingElement($grid, $playerId, ANIMAL_CROCODILE, ANIMAL_GIRAFFE, null);
            case 2:
                return $this->calculateGoalAnimalTouchingElement($grid, $playerId, ANIMAL_GORILLA, null, LAND_WATER);
            default:
                # code...
                break;
        }
        return 0;
    }

    function getGrid($playerId) {
        $gridSize = 6;
        $grid = [];

        for ($i = 0; $i < $gridSize; $i++) {
            $grid[] = array_fill(0, $gridSize, null);
        }
        return $grid;
    }

    function calculateGoalAnimalTouchingElement($grid, $animal, $otherAnimal, $landType) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains a crocodile
                if ($grid[$row][$col] == $animal) {
                    // Check adjacent cells (orthogonally)
                    if ($otherAnimal && $row > 0 && $grid[$row - 1][$col] == $otherAnimal) {
                        $points += 2;  // Crocodile touching a giraffe from above
                    }
                    if ($otherAnimal && $row < $gridSize - 1 && $grid[$row + 1][$col] == $otherAnimal) {
                        $points += 2;  // Crocodile touching a giraffe from below
                    }
                    if ($otherAnimal && $col > 0 && $grid[$row][$col - 1] == $otherAnimal) {
                        $points += 2;  // Crocodile touching a giraffe to the left
                    }
                    if ($otherAnimal && $col < count($grid[$row]) - 1 && $grid[$row][$col + 1] == $otherAnimal) {
                        $points += 2;  // Crocodile touching a giraffe to the right
                    }
                }
            }
        }

        return $points;
    }
}
