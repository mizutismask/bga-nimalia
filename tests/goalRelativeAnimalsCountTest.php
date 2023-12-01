<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once('../nimalia.game.php');

class GoalRelativeAnimalsCountTest extends Nimalia { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /** Redefine some function of the game to mock data. Todo : rename getData to match your function */
    /*
    3 players, no tie
    */
    function getGrid($playerId = null) {
        $grid = $this->initGrid();
        //no tie
        /* if ($playerId == 1) {
            $grid[0][1] = new Biome(ANIMAL_LION);
            $grid[0][2] = new Biome(ANIMAL_LION);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        } else if ($playerId == 2) {
            $grid[0][1] = new Biome(ANIMAL_PANDA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_GORILLA);
        } else if ($playerId == 3) {
            $grid[0][1] = new Biome(ANIMAL_PANDA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        }*/

        //tie
        if ($playerId == 1) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_LION);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        } else if ($playerId == 2) {
            $grid[0][1] = new Biome(ANIMAL_PANDA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_GORILLA);
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

    function initGrid() {
        $grid = [];
        $fakeBiome = new Biome(0, -1, 0); //to avoid null errors
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $grid[] = array_fill(0, GRID_SIZE, $fakeBiome);
        }
        return $grid;
    }

    // class tests
    function testGoalRelativeAnimalsCount() {

        /**no tie */
        //$result = $this->calculateGoalRelativeAnimalsCount(2, ANIMAL_GORILLA, false, 5, 2);//5
        //$result = $this->calculateGoalRelativeAnimalsCount(3, ANIMAL_GORILLA, false, 5, 2);//2
        $result = $this->calculateGoalRelativeAnimalsCount(1, ANIMAL_GORILLA, false, 5, 2);//0

        /**tie */
        //$result = $this->calculateGoalRelativeAnimalsCount(2, ANIMAL_GORILLA, false, 5, 2); //5
        //$result = $this->calculateGoalRelativeAnimalsCount(3, ANIMAL_GORILLA, false, 5, 2); //5
        //$result = $this->calculateGoalRelativeAnimalsCount(1, ANIMAL_GORILLA, false, 5, 2); //0

        $equal = $result ==0;

        if ($equal) {
            echo "Test3: PASSED\n";
        } else {
            echo "Test3: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testAll() {
        $this->testGoalRelativeAnimalsCount();
    }
}

$test1 = new GoalRelativeAnimalsCountTest();
$test1->testAll();
