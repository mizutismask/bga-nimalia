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
                return array_values(array_filter($goals, fn ($g) => $g->color == GOAL_BLUE || $g->color == GOAL_GREEN));
            case 2:
                return array_values(array_filter($goals, fn ($g) => $g->color == GOAL_YELLOW || $g->color == GOAL_GREEN));
            case 3:
                return array_values(array_filter($goals, fn ($g) => $g->color == GOAL_BLUE || $g->color == GOAL_RED));
            case 4:
                return array_values(array_filter($goals, fn ($g) => $g->color == GOAL_GREEN || $g->color == GOAL_YELLOW || $g->color == GOAL_RED));
            case 5:
                return array_values(array_filter($goals, fn ($g) => $g->color == GOAL_BLUE || $g->color == GOAL_YELLOW || $g->color == GOAL_RED));

            default:
                throw new BgaVisibleSystemException("this round is never supposed to happen : " . $round);
        }
    }

    /** Calculates points for a given goal and a given player. Called at the end of each round. */
    public function calculateGoalPoints($goal, $playerId) {
        return 0;
    }
}
