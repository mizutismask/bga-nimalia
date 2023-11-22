<?php

require_once(__DIR__ . '/objects/Goal.php');
trait GoalTrait {

    public function selectGoals() {
        $goals = [];
        foreach (GOAL_COLORS as $color) {
            $coloredGoals = array_values(array_filter($this->GOALS, fn ($g) => $g->color === $color));
            $randIndex = bga_rand(0, count($coloredGoals) - 1);
            $goals[]=$coloredGoals[$randIndex];
        }
        $this->setGlobalVariable("GOALS", $goals);
    }
}
