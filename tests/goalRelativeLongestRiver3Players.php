<?php
require_once('./gameBaseTest.php');

class GoalRelativeLongestRiver3Players extends GameTestBase { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /** Redefine some function of the game to mock data. Todo : rename getData to match your function */
    /*
    3 players, tie on first place
    */
    function getGrid($playerId = null) {
        $grid = $this->initGrid();
        if ($playerId == 1) {
            $grid[2][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[3][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[4][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[4][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[3][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[2][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        } else if ($playerId == 2) {
            $grid[1][0] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[1][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[3][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[3][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[4][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        } else if ($playerId == 3) {
            $grid[3][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
            $grid[4][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        }
        return $grid;
    }

    function getPlayersIds() {
        return [1, 2, 3];
    }

    function testPlayer1LongestRiverScore() {
        $result = $this->calculateGoalLongestRiverAmongPlayers(1, 5, 2);
        $equal = $result == 5;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer2LongestRiverScore() {
        $result = $this->calculateGoalLongestRiverAmongPlayers(2, 5, 2);
        $equal = $result == 5;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer3LongestRiverScore() {
        $result = $this->calculateGoalLongestRiverAmongPlayers(3, 5, 2);
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testAll() {
        $this->testPlayer1LongestRiverScore();
        $this->testPlayer2LongestRiverScore();
        $this->testPlayer3LongestRiverScore();
    }
}

$test1 = new GoalRelativeLongestRiver3Players();
$test1->testAll();
