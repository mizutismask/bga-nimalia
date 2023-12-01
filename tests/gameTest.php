<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once('../nimalia.game.php');

class GameTest extends Nimalia { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /** Redefine some function of the game to mock data. Todo : rename getData to match your function */
    function getData($playerId = null) {

        return [];
    }

    // class tests
    function testcalculateGoalAnimalTouchingAtLeastOneKindOfElement() {
        $grid = [];
        $fakeBiome = new Biome(0, -1, 0); //to avoid null errors
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $grid[] = array_fill(0, GRID_SIZE, $fakeBiome);
        }

        //from rule case
        $grid[0][1] = new Biome(ANIMAL_GIRAFFE);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_CROCODILE);
        $grid[2][1] = new Biome(ANIMAL_GIRAFFE);
        $grid[2][2] = new Biome(ANIMAL_CROCODILE);
        $this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingAtLeastOneKindOfElement($grid, ANIMAL_CROCODILE, ANIMAL_GIRAFFE);

        $equal = $result == 4;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testAll() {
        $this->testcalculateGoalAnimalTouchingAtLeastOneKindOfElement();
    }
}

$test1 = new GameTest();
$test1->testAll();
