<?php
require_once('./gameBaseTest.php');

class GoalRelativeAnimalsCountTestWithNoPanda extends GameTestBase { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /*
    3 players, tie on no pandas for first place
    */
    function getGrid($playerId = null) {
        $grid = $this->initGrid();
        if ($playerId == 1) {
            $grid[0][1] = new Biome(ANIMAL_LION);
            $grid[0][2] = new Biome(ANIMAL_LION);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        } else if ($playerId == 2) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_CROCODILE);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        } else if ($playerId == 3) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        }
        return $grid;
    }

    function getPlayersIds() {
        return [1, 2, 3];
    }

    // class tests
    function testGoalRelativeAnimalsCountNoOneHasPanda() {
        $result = $this->calculateGoalRelativeAnimalsCount(1, ANIMAL_PANDA, false, -5, -2); //0
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);

        $result = $this->calculateGoalRelativeAnimalsCount(2, ANIMAL_PANDA, false, -5, -2); //0
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);

        $result = $this->calculateGoalRelativeAnimalsCount(3, ANIMAL_PANDA, false, -5, -2); //0
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testAll() {
        $this->testGoalRelativeAnimalsCountNoOneHasPanda();
    }
}

$test1 = new GoalRelativeAnimalsCountTestWithNoPanda();
$test1->testAll();
