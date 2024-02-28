<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once('../nimalia.game.php');

class GoalRelativeAnimalsCountTest4Players extends Nimalia { // this is your game class defined in ggg.game.php
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
        //tie
        if ($playerId == 1) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_GORILLA);
            $grid[1][1] = new Biome(ANIMAL_PANDA); //3 gorillas(0 points) 1 pandas (0 points)
        } else if ($playerId == 2) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_GORILLA);
            $grid[1][1] = new Biome(ANIMAL_GORILLA);
            $grid[1][2] = new Biome(ANIMAL_PANDA);
            $grid[1][3] = new Biome(ANIMAL_PANDA); //4 gorillas(2 points) 2 pandas (-2 points)
        } else if ($playerId == 3) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[0][3] = new Biome(ANIMAL_GORILLA);
            $grid[0][4] = new Biome(ANIMAL_GORILLA);
            $grid[0][5] = new Biome(ANIMAL_GORILLA);
            $grid[0][6] = new Biome(ANIMAL_GORILLA);
            $grid[1][1] = new Biome(ANIMAL_GORILLA);
            $grid[1][2] = new Biome(ANIMAL_PANDA);
            $grid[1][3] = new Biome(ANIMAL_PANDA);
            $grid[1][4] = new Biome(ANIMAL_PANDA); //7 gorillas(5 points) 3 pandas (-5 points)
        } else if ($playerId == 4) {
            $grid[0][1] = new Biome(ANIMAL_GORILLA);
            $grid[0][2] = new Biome(ANIMAL_GORILLA);
            $grid[1][0] = new Biome(ANIMAL_PANDA);
            $grid[1][1] = new Biome(ANIMAL_PANDA); //2 gorillas(0 points) 2 pandas (-2 points)
        }
        return $grid;
    }

    function getPlayersIds() {
        return [1, 2, 3, 4];
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
    function displayResult($testName, $equal, $result) {
        echo ($testName);
        if ($equal) {
            echo " : PASSED\n";
        } else {
            echo " : FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testPlayer1GorillaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(1, ANIMAL_GORILLA, false, 5, 2);
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer1PandaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(1, ANIMAL_PANDA, false, -5, -2);
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testPlayer2GorillaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(2, ANIMAL_GORILLA, false, 5, 2);
        $equal = $result == 2;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer2PandaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(2, ANIMAL_PANDA, false, -5, -2);
        $equal = $result == -2;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer3GorillaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(3, ANIMAL_GORILLA, false, 5, 2);
        $equal = $result == 5;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer3PandaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(3, ANIMAL_PANDA, false, -5, -2);
        $equal = $result == -5;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer4GorillaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(4, ANIMAL_GORILLA, false, 5, 2);
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testPlayer4PandaScore() {
        $result = $this->calculateGoalRelativeAnimalsCount(4, ANIMAL_PANDA, false, -5, -2);
        $equal = $result == -2;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testAll() {
        $this->testPlayer1GorillaScore();
        $this->testPlayer1PandaScore();
        $this->testPlayer2GorillaScore();
        $this->testPlayer2PandaScore();
        $this->testPlayer3GorillaScore();
        $this->testPlayer3PandaScore();
        $this->testPlayer4GorillaScore();
        $this->testPlayer4PandaScore();
    }
}

$test1 = new GoalRelativeAnimalsCountTest4Players();
$test1->testAll();
