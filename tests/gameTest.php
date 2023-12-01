<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once('../nimalia.game.php');

class GameTest extends Nimalia { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    /** Redefine some function of the game to mock data. Todo : rename getData to match your function */
    function getGrid($playerId = null) {
        $grid = $this->initGrid();
        if ($playerId == 1) {
            $grid[0][1] = new Biome(ANIMAL_LION);
            $grid[0][2] = new Biome(ANIMAL_LION);
            $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        } else if ($playerId == 2) {
            $grid[0][1] = new Biome(ANIMAL_PANDA);
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
    function testcalculateGoalAnimalTouchingAtLeastOneKindOfAnimal() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][1] = new Biome(ANIMAL_GIRAFFE);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_CROCODILE);
        $grid[2][1] = new Biome(ANIMAL_GIRAFFE);
        $grid[2][2] = new Biome(ANIMAL_CROCODILE);
        $this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingAtLeastOneOtherAnimal($grid, ANIMAL_CROCODILE, ANIMAL_GIRAFFE);

        $equal = $result == 4;

        if ($equal) {
            echo "Test3: PASSED\n";
        } else {
            echo "Test3: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testcalculateGoalAnimalTouchingAtLeastOneLand() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_GORILLA);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][2] = new Biome(ANIMAL_GORILLA);
        $grid[2][0] = new Biome(ANIMAL_GORILLA);
        $grid[2][1] = new Biome(ANIMAL_CROCODILE);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingAtLeastOneKindOfLand($grid, ANIMAL_GORILLA, LAND_WATER);

        $equal = $result == 4;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalLeastLionsFalse() {
        $result = $this->calculateGoalLeastLions(1);
        $equal = $result == -2;
        if ($equal) {
            echo "Test2: PASSED\n";
        } else {
            echo "Test2: FAILED\n";
            echo "Found: $result\n";
        }
    }



    function testCalculateGoalLeastLionsTrue() {
        $result = $this->calculateGoalLeastLions(2);
        $equal = $result == 3;
        if ($equal) {
            echo "Test4: PASSED\n";
        } else {
            echo "Test4: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalExactlyOneAnimalOfTypePerColonne() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_PINGUIN);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_PINGUIN);
        $grid[2][1] = new Biome(ANIMAL_PINGUIN);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalExactlyOneAnimalOfTypePerColonne($grid, ANIMAL_PINGUIN);
        $equal = $result == 3;
        if ($equal) {
            echo "Test5: PASSED\n";
        } else {
            echo "Test5: FAILED\n";
            echo "Found: $result\n";
        }
    }
    function testCalculateLandZonesNone() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_PINGUIN);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_PINGUIN);
        $grid[2][1] = new Biome(ANIMAL_PINGUIN);
        // $this->displayGrid($grid);

        $result = $this->calculateLandZones($grid, LAND_SAVANNAH);
        $equal = $result == 0;
        if ($equal) {
            echo "Test6: PASSED\n";
        } else {
            echo "Test6: FAILED\n";
            echo "Found: $result\n";
        }
    }
    function testCalculateLandZonesOne() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_LION);
        $grid[0][1] = new Biome(ANIMAL_LION);
        $grid[1][1] = new Biome(ANIMAL_LION);
        $grid[1][2] = new Biome(ANIMAL_LION);
        $this->displayGrid($grid);

        $result = $this->calculateLandZones($grid, LAND_SAVANNAH);
        $equal = $result == 6;
        if ($equal) {
            echo "Test7: PASSED\n";
        } else {
            echo "Test7: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalCompleteSquareNone() {
        $grid = $this->initGrid();

        //from rule case
        $grid[5][5] = new Biome(ANIMAL_LION);
        $grid[5][4] = new Biome(ANIMAL_LION);
        $grid[4][5] = new Biome(ANIMAL_LION);
        $grid[4][4] = new Biome(ANIMAL_LION);
        $this->displayGrid($grid);

        $result = $this->calculateGoalCompleteSquare($grid);
        $equal = $result == 0;
        if ($equal) {
            echo "Test8: PASSED\n";
        } else {
            echo "Test8: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalCompleteSquare21() {
        $grid = $this->initGrid();

        //from rule case
        for ($i = 0; $i < GRID_SIZE; $i++) {
            for ($j = 0; $j < GRID_SIZE; $j++) {
                $grid[$i][$j] = new Biome(ANIMAL_LION);
            }
        }
        // $this->displayGrid($grid);

        $result = $this->calculateGoalCompleteSquare($grid);
        $equal = $result == 21;
        if ($equal) {
            echo "Test9: PASSED\n";
        } else {
            echo "Test9: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalCompleteSquare4() {
        $grid = $this->initGrid();

        //from rule case
        $grid[5][1] = new Biome(ANIMAL_LION);
        $grid[5][0] = new Biome(ANIMAL_LION);
        $grid[4][0] = new Biome(ANIMAL_LION);
        $grid[4][1] = new Biome(ANIMAL_LION);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalCompleteSquare($grid);
        $equal = $result == 3;
        if ($equal) {
            echo "Test10: PASSED\n";
        } else {
            echo "Test10: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testCalculateGoalCompleteSquareWithHole() {
        $grid = $this->initGrid();

        //from rule case
        $grid[5][1] = new Biome(ANIMAL_LION);
        $grid[5][0] = new Biome(ANIMAL_LION);
        $grid[4][0] = new Biome(ANIMAL_LION);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalCompleteSquare($grid);
        $equal = $result == 0;
        if ($equal) {
            echo "Test11: PASSED\n";
        } else {
            echo "Test11: FAILED\n";
            echo "Found: $result\n";
        }
    }

    function testAll() {
        $this->testcalculateGoalAnimalTouchingAtLeastOneKindOfAnimal();
        $this->testcalculateGoalAnimalTouchingAtLeastOneLand();
        $this->testCalculateGoalLeastLionsFalse();
        $this->testCalculateGoalLeastLionsTrue();
        $this->testCalculateGoalExactlyOneAnimalOfTypePerColonne();
        $this->testCalculateLandZonesNone();
        $this->testCalculateLandZonesOne();
        $this->testCalculateGoalCompleteSquareNone();
        $this->testCalculateGoalCompleteSquare21();
        $this->testCalculateGoalCompleteSquare4();
        $this->testCalculateGoalCompleteSquareWithHole();
    }
}

$test1 = new GameTest();
$test1->testAll();
