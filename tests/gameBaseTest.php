<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once('../nimalia.game.php');

abstract class GameTestBase extends Nimalia { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php'; // this is how this normally included, from constructor
    }

    abstract function testAll();

    /**
     * To redefine if players count is not 3
     */
    function getPlayersIds() {
        return [1, 2, 3];
    }

    /*
    To redefine to mock data.
    */
    function getGrid($playerId = null) {
        return $this->initGrid();
    }

    function initGrid() {
        $grid = [];
        $fakeBiome = new Biome(0, -1, 0); //to avoid null errors
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $grid[] = array_fill(0, GRID_SIZE, $fakeBiome);
        }
        return $grid;
    }

    function convertNumbersToGrid(string $textGrid) {
        $grid = $this->initGrid();
        $rows = preg_split("/\r\n|\n|\r/", trim($textGrid));
        foreach ($rows as $iRow => $row) {
            $row = trim($row);
            //self::dump('', compact("iRow", "row"));
            for ($iCol = 0; $iCol < GRID_SIZE; $iCol++) {
                $animal = intval($row[$iCol]);
                //self::dump('', compact("iRow", "iCol", "animal"));
                if ($animal === 0) {
                    $biome = new Biome(0, -1, 0);
                } else if ($animal === ANIMAL_OTTER) {
                    $biome = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
                    self::dump(
                        'WARNING, you might have to specify manually land and river for otter in ',
                        implode(["[", $iRow, "]", "[", $iCol, "]"])
                    );
                } else {
                    $biome = new Biome($animal);
                }
                $grid[$iRow][$iCol] = $biome;
            }
        }
        //$this->displayGrid($grid);
        return $grid;
    }

    function displayResult($testName, $equal, $result) {
        echo ($testName);
        if ($equal) {
            echo " : PASSED\n";
        } else {
            echo " : FAILED\n";
            echo "Found: $result\n";
        }
    }
    function testExemple() {
        //get this typing displayPlayerGrid() in the chat, remove the last number of each line except the last one
        $grid = $this->convertNumbersToGrid("
            121641
            355182
            245716
            974779
            258725
            383687
        ");

        //give more info for otters
        $grid[2][3] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN);
        $grid[3][1] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN);
        $grid[3][3] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP);
        $grid[3][4] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN);
        $grid[4][3] = new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN);
        $grid[4][3] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN);

        $result = $this->calculateGoalRiverConnectedToLand($grid, LAND_WATER);

        //test result
        $equal = $result == 10;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
}
