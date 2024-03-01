<?php
require_once('./gameBaseTest.php');

class GoalRelativeLongestRiver2Players extends GameTestBase { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /** Redefine some function of the game to mock data. Todo : rename getData to match your function */
    /*
    2 players, tie on first place
    */
    function getGrid($playerId = null) {
        $grid = $this->initGrid();
        if ($playerId == 1) {

            //get this typing displayPlayerGrid() in the chat, remove the last number of each line except the last one
            $grid = $this->convertNumbersToGrid("
                000000
                024000
                477053
                337292
                087716
                009651
            ");

            //give more info for otters
            $grid[2][1] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP);
            $grid[2][2] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN);
            $grid[3][2] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP);
            $grid[4][2] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN);
            $grid[4][3] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP);
        } else if ($playerId == 2) {
            $grid = $this->convertNumbersToGrid("
                079000
                017835
                086762
                121576
                542175
                000000
            ");

            $grid[0][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[1][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[2][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[3][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
            $grid[5][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        }
        return $grid;
    }

    function getPlayersIds() {
        return [1, 2];
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

    function testAll() {
        $this->testPlayer1LongestRiverScore();
        $this->testPlayer2LongestRiverScore();
    }
}

$test1 = new GoalRelativeLongestRiver2Players();
$test1->testAll();
