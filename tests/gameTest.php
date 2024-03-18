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

    function testBiomesCorrectDescription() {
        foreach ($this->BIOMES_CARDS[1] as $id => $desc) {
            foreach ($desc->biomes as $biome) {
                if ($biome->animal == ANIMAL_OTTER) {
                    if ($biome->land <= 0) {
                        throw new BgaVisibleSystemException("Biome incorrect in material, missing land : " . json_encode($desc));
                    }
                    if ($biome->river <= 0) {
                        throw new BgaVisibleSystemException("Biome incorrect in material, missing river direction : " . json_encode($desc));
                    }
                }
            }
        }
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
        //$this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingAtLeastOneOtherAnimal($grid, ANIMAL_CROCODILE, ANIMAL_GIRAFFE);

        $equal = $result == 4;

        $this->displayResult(__FUNCTION__, $equal, $result);
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

        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalLeastLionsFalse() {
        $result = $this->calculateGoalLeastLions(1);
        $equal = $result == -2;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalLeastLionsTrue() {
        $result = $this->calculateGoalLeastLions(2);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
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
        $this->displayResult(__FUNCTION__, $equal, $result);
    }
    function testCalculateLandZonesNone() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_PINGUIN);
        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_PINGUIN);
        $grid[2][1] = new Biome(ANIMAL_PINGUIN);
        // $this->displayGrid($grid);

        $result = $this->calculateGoalLandZonesSize4($grid, LAND_SAVANNAH);
        $equal = $result == 0;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLandZonesOne() {
        $grid = $this->initGrid();

        $grid[0][0] = new Biome(ANIMAL_LION);
        $grid[0][1] = new Biome(ANIMAL_LION);
        $grid[1][0] = new Biome(ANIMAL_LION);
        $grid[1][1] = new Biome(ANIMAL_LION);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalLandZonesSize4($grid, LAND_SAVANNAH);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLandZonesRuleCase() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_LION);
        $grid[0][1] = new Biome(ANIMAL_LION);
        $grid[0][4] = new Biome(ANIMAL_LION);

        $grid[1][0] = new Biome(ANIMAL_LION);
        $grid[1][1] = new Biome(ANIMAL_LION);
        $grid[1][4] = new Biome(ANIMAL_LION);

        $grid[2][3] = new Biome(ANIMAL_LION);
        $grid[2][4] = new Biome(ANIMAL_LION);

        $grid[3][1] = new Biome(ANIMAL_LION);
        $grid[3][2] = new Biome(ANIMAL_LION);

        //$this->displayGrid($grid);
        $result = $this->calculateGoalLandZonesSize4($grid, LAND_SAVANNAH);
        $equal = $result == 12;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalCompleteSquareAnywhere() {
        $grid = $this->initGrid();

        $grid[5][5] = new Biome(ANIMAL_LION);
        $grid[5][4] = new Biome(ANIMAL_LION);
        $grid[4][5] = new Biome(ANIMAL_LION);
        $grid[4][4] = new Biome(ANIMAL_LION);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalCompleteSquare($grid);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
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
        $this->displayResult(__FUNCTION__, $equal, $result);
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
        $this->displayResult(__FUNCTION__, $equal, $result);
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
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalAnimalTouchingBorder() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][2] = new Biome(ANIMAL_LION);
        $grid[0][3] = new Biome(ANIMAL_LION);
        $grid[1][0] = new Biome(ANIMAL_LION);
        $grid[1][1] = new Biome(ANIMAL_LION);
        $grid[2][0] = new Biome(ANIMAL_LION);
        $grid[2][2] = new Biome(ANIMAL_LION);
        $grid[2][3] = new Biome(ANIMAL_LION);
        $grid[3][1] = new Biome(ANIMAL_LION);

        $grid[1][2] = new Biome(ANIMAL_PINGUIN);
        $grid[1][3] = new Biome(ANIMAL_PINGUIN);
        $grid[2][1] = new Biome(ANIMAL_PINGUIN);
        $grid[3][2] = new Biome(ANIMAL_PINGUIN);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingBorder($grid, ANIMAL_PINGUIN, true);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalAnimalNotTouchingBorder() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][2] = new Biome(ANIMAL_LION);
        $grid[0][3] = new Biome(ANIMAL_LION);
        $grid[1][0] = new Biome(ANIMAL_LION);
        $grid[1][3] = new Biome(ANIMAL_LION);
        $grid[2][0] = new Biome(ANIMAL_LION);
        $grid[2][2] = new Biome(ANIMAL_LION);
        $grid[2][3] = new Biome(ANIMAL_LION);
        $grid[3][1] = new Biome(ANIMAL_LION);
        $grid[3][2] = new Biome(ANIMAL_LION);

        $grid[1][1] = new Biome(ANIMAL_FLAMINGO);
        $grid[1][2] = new Biome(ANIMAL_FLAMINGO);
        $grid[2][1] = new Biome(ANIMAL_FLAMINGO);
        $grid[3][3] = new Biome(ANIMAL_FLAMINGO);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalAnimalTouchingBorder($grid, ANIMAL_FLAMINGO, false);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalLineWithAllLands() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_PINGUIN);
        $grid[0][1] = new Biome(ANIMAL_LION);
        $grid[0][2] = new Biome(ANIMAL_GORILLA);
        $grid[0][3] = new Biome(ANIMAL_CROCODILE);

        $grid[1][0] = new Biome(ANIMAL_PINGUIN);
        $grid[1][1] = new Biome(ANIMAL_CROCODILE);

        $grid[2][0] = new Biome(ANIMAL_GORILLA);
        $grid[2][1] = new Biome(ANIMAL_CROCODILE);
        $grid[2][2] = new Biome(ANIMAL_LION);
        $grid[2][3] = new Biome(ANIMAL_PINGUIN);
        $grid[2][4] = new Biome(ANIMAL_GORILLA);

        $grid[3][0] = new Biome(ANIMAL_CROCODILE);
        $grid[3][1] = new Biome(ANIMAL_PINGUIN);
        $grid[3][2] = new Biome(ANIMAL_GORILLA);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalLineWithAllLands($grid, ANIMAL_FLAMINGO, false);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalSmallestLandSquares() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][1] = new Biome(ANIMAL_LION);
        $grid[0][2] = new Biome(ANIMAL_LION);
        $grid[0][4] = new Biome(ANIMAL_GORILLA);
        $grid[0][5] = new Biome(ANIMAL_GORILLA);

        $grid[1][0] = new Biome(ANIMAL_CROCODILE);
        $grid[1][1] = new Biome(ANIMAL_LION);
        $grid[1][2] = new Biome(ANIMAL_LION);
        $grid[1][3] = new Biome(ANIMAL_GORILLA);
        $grid[1][4] = new Biome(ANIMAL_GORILLA);
        $grid[1][5] = new Biome(ANIMAL_GORILLA);

        $grid[2][0] = new Biome(ANIMAL_CROCODILE);
        $grid[2][1] = new Biome(ANIMAL_LION);
        $grid[2][2] = new Biome(ANIMAL_PINGUIN);
        $grid[2][3] = new Biome(ANIMAL_PINGUIN);
        $grid[2][4] = new Biome(ANIMAL_CROCODILE);
        $grid[2][5] = new Biome(ANIMAL_GORILLA);

        $grid[3][0] = new Biome(ANIMAL_PINGUIN);
        $grid[3][1] = new Biome(ANIMAL_CROCODILE);
        $grid[3][2] = new Biome(ANIMAL_CROCODILE);
        $grid[3][3] = new Biome(ANIMAL_CROCODILE);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalSmallestLandSquares($grid);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoal2x2Squares() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][2] = new Biome(ANIMAL_PINGUIN);
        $grid[0][3] = new Biome(ANIMAL_PINGUIN);

        $grid[1][0] = new Biome(ANIMAL_PINGUIN);
        $grid[1][1] = new Biome(ANIMAL_PINGUIN);
        $grid[1][2] = new Biome(ANIMAL_PINGUIN);
        $grid[1][3] = new Biome(ANIMAL_PINGUIN);

        $grid[2][0] = new Biome(ANIMAL_PINGUIN);
        $grid[2][1] = new Biome(ANIMAL_PINGUIN);
        $grid[2][2] = new Biome(ANIMAL_PINGUIN);

        //$this->displayGrid($grid);

        $result = $this->calculateGoal2x2Squares(
            $grid,
            LAND_SNOW
        );
        $equal = $result == 12;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalRiverConnectedToLand() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][0] = new Biome(ANIMAL_CROCODILE);
        $grid[0][3] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);

        $grid[1][1] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[1][2] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[1][3] = new Biome(ANIMAL_CROCODILE);

        $grid[2][0] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[2][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[2][3] = new Biome(ANIMAL_CROCODILE);

        //$this->displayGrid($grid);

        $result = $this->calculateGoalRiverConnectedToLand($grid, LAND_WATER);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalRiverConnectedToLandHorizontaly() {
        $grid = $this->initGrid();

        //from bug
        $grid[0][3] = new Biome(ANIMAL_LION);
        $grid[0][4] = new Biome(ANIMAL_GIRAFFE);

        $grid[1][2] = new Biome(ANIMAL_CROCODILE);
        $grid[1][3] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP);
        $grid[1][4] = new Biome(ANIMAL_PINGUIN);

        $grid[2][1] =  new Biome(ANIMAL_BEAR);
        $grid[2][2] =  new Biome(ANIMAL_GIRAFFE);
        $grid[2][3] = new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN);

        $grid[3][1] =  new Biome(ANIMAL_GORILLA);
        $grid[3][2] =  new Biome(ANIMAL_BEAR);

        //$this->displayGrid($grid);

        $result = $this->calculateGoalRiverConnectedToLand($grid, LAND_WATER);
        $equal = $result == 4;
        $this->displayResult(__FUNCTION__, $equal, $result);
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

    function testGetRotatedBiomesAndRivers90Degrees() {
        $card = new BiomeCard(['type' => 1, 'type_arg' => 36, 'id' => 1, 'location' => "toto", 'location_arg' => "squareToto"], $this->BIOMES_CARDS, ['card_rotation' => 90, 'card_order_in_grid' => 1]);
        /* self::dump('*******************before rotation',
            $card
        );*/
        $rotated = $this->getRotatedBiomesAndRivers($card);
        //self::dump('*******************rotated', $rotated);
        $this->displayResult(__FUNCTION__, $rotated[0]->animal == ANIMAL_OTTER, $rotated[0]->animal);
        $this->displayResult(__FUNCTION__, $rotated[0]->river == RIVER_DOWN, $rotated[0]->river);
        $this->displayResult(__FUNCTION__, $rotated[1]->animal == ANIMAL_PANDA, $rotated[1]->animal);
        $this->displayResult(__FUNCTION__, $rotated[2]->animal == ANIMAL_GORILLA, $rotated[2]->animal);
        $this->displayResult(__FUNCTION__, $rotated[3]->animal == ANIMAL_FLAMINGO, $rotated[3]->animal);
    }

    function testGetRotatedBiomesAndRivers180Degrees() {
        $card = new BiomeCard(['type' => 1, 'type_arg' => 36, 'id' => 1, 'location' => "toto", 'location_arg' => "squareToto"], $this->BIOMES_CARDS, ['card_rotation' => 180, 'card_order_in_grid' => 1]);
        /* self::dump('*******************before rotation',
            $card
        );*/
        $rotated = $this->getRotatedBiomesAndRivers($card);
        //self::dump('*******************rotated', $rotated);
        $this->displayResult(__FUNCTION__, $rotated[0]->animal == ANIMAL_GORILLA, $rotated[0]->animal);
        $this->displayResult(__FUNCTION__, $rotated[1]->animal == ANIMAL_OTTER, $rotated[1]->animal);
        $this->displayResult(__FUNCTION__, $rotated[1]->river == RIVER_UP, $rotated[1]->river);
        $this->displayResult(__FUNCTION__, $rotated[2]->animal == ANIMAL_FLAMINGO, $rotated[2]->animal);
        $this->displayResult(__FUNCTION__, $rotated[3]->animal == ANIMAL_PANDA, $rotated[3]->animal);
    }

    function testGetRotatedBiomesAndRivers270Degrees() {
        $card = new BiomeCard(['type' => 1, 'type_arg' => 36, 'id' => 1, 'location' => "toto", 'location_arg' => "squareToto"], $this->BIOMES_CARDS, ['card_rotation' => 270, 'card_order_in_grid' => 1]);
        /* self::dump('*******************before rotation',
            $card
        );*/
        $rotated = $this->getRotatedBiomesAndRivers($card);
        //self::dump('*******************rotated', $rotated);
        $this->displayResult(__FUNCTION__, $rotated[0]->animal == ANIMAL_FLAMINGO, $rotated[0]->animal);
        $this->displayResult(__FUNCTION__, $rotated[1]->animal == ANIMAL_GORILLA, $rotated[1]->animal);
        $this->displayResult(__FUNCTION__, $rotated[2]->animal == ANIMAL_PANDA, $rotated[2]->animal);
        $this->displayResult(__FUNCTION__, $rotated[3]->animal == ANIMAL_OTTER, $rotated[3]->animal);
        $this->displayResult(__FUNCTION__, $rotated[3]->river == RIVER_DOWN, $rotated[3]->river);
    }

    function testCalculateLargestRiverOneLongContinous() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][2] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[0][3] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);

        $grid[1][1] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[1][3] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        $grid[2][0] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[2][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLargestRiverOneWithLoop() {
        $grid = $this->initGrid();

        $grid[1][1] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[1][2] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_UP
        );
        $grid[1][3] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);


        $grid[2][1] =  new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_UP
        );
        $grid[2][2] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[2][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLargestRiver3Rivers() {
        $grid = $this->initGrid();

        $grid[0][0] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[0][2] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[0][3] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);

        $grid[2][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[2][2] = new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[2][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLargestRiverOneLongContinousLength7() {
        $grid = $this->initGrid();

        //from rule case
        $grid[0][4] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_UP
        );
        $grid[1][3] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_UP
        );
        $grid[2][2] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_UP
        );
        $grid[3][2] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_DOWN
        );
        $grid[3][4] = new Biome(
            ANIMAL_OTTER,
            LAND_SNOW,
            RIVER_DOWN
        );
        $grid[4][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[4][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 7;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }


    /*
    000000
    000000
    00/00\
    00\00/
    000//0
    000000
 */
    function testCalculateLargestRiverOf2() {
        $grid = $this->initGrid();

        $grid[2][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[3][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[4][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[4][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[3][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[2][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateLargestSnakeRiver() {
        $grid = $this->initGrid();

        $grid[0][0] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[0][1] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[0][2] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[0][3] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);
        $grid[0][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN);
        $grid[0][5] =  new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP);

        //$this->displayRiverGrid($grid);

        $result = $this->calculateLargestRiver($grid);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalLandZonesCount() {
        $grid = $this->initGrid();

        $grid[0][1] = new Biome(ANIMAL_GORILLA);
        $grid[1][1] = new Biome(ANIMAL_GORILLA);
        $grid[1][3] = new Biome(ANIMAL_GORILLA);

        $grid[2][0] =  new Biome(ANIMAL_GORILLA);

        //$this->displayGrid($grid);

        $result = $this->calculateGoalLandZonesCount($grid, LAND_JUNGLE);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalAnimalZonesCount() {
        $grid = $this->initGrid();
        $grid[0][0] = new Biome(ANIMAL_BEAR);
        $grid[0][1] = new Biome(ANIMAL_BEAR);
        $grid[0][3] = new Biome(ANIMAL_BEAR);

        $grid[1][0] =  new Biome(ANIMAL_BEAR);

        $grid[2][0] =  new Biome(ANIMAL_BEAR);
        $grid[2][2] =  new Biome(ANIMAL_BEAR);
        $grid[2][3] =  new Biome(ANIMAL_BEAR);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalAnimalZonesCount($grid, ANIMAL_BEAR);
        $equal = $result == 11;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalBiggestSavannah() {
        $grid = $this->initGrid();
        $grid[0][0] = new Biome(ANIMAL_LION);
        $grid[0][2] = new Biome(ANIMAL_LION);

        $grid[1][0] =  new Biome(ANIMAL_LION);
        $grid[1][2] = new Biome(ANIMAL_LION);
        $grid[1][3] = new Biome(ANIMAL_LION);

        $grid[2][2] =  new Biome(ANIMAL_LION);
        $grid[2][3] =  new Biome(ANIMAL_LION);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalBiggestSavannah($grid);
        $equal = $result == 10;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoal2HorizontalAnimals() {
        $grid = $this->initGrid();
        $grid[0][0] = new Biome(ANIMAL_BEAR);
        $grid[0][1] = new Biome(ANIMAL_BEAR);
        $grid[0][2] = new Biome(ANIMAL_BEAR);

        $grid[1][0] =  new Biome(ANIMAL_LION);
        $grid[2][0] = new Biome(ANIMAL_LION);

        $grid[2][1] =  new Biome(ANIMAL_CROCODILE);
        $grid[2][2] =  new Biome(ANIMAL_CROCODILE);
        //$this->displayGrid($grid);

        $result = $this->calculateGoal2HorizontalAnimals($grid);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoal2HorizontalAnimalsWithOneUnder() {
        $grid = $this->initGrid();
        $grid[0][0] = new Biome(ANIMAL_BEAR);
        $grid[0][1] = new Biome(ANIMAL_BEAR);
        $grid[1][0] = new Biome(ANIMAL_BEAR);
        //$this->displayGrid($grid);

        $result = $this->calculateGoal2HorizontalAnimals($grid);
        $equal = $result == 3;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoal2HorizontalAnimalsWith2OthersUnder() {
        $grid = $this->initGrid();
        $grid[0][0] = new Biome(ANIMAL_BEAR);
        $grid[0][1] = new Biome(ANIMAL_BEAR);
        $grid[1][0] = new Biome(ANIMAL_BEAR);
        $grid[1][1] = new Biome(ANIMAL_BEAR);
        //$this->displayGrid($grid);

        $result = $this->calculateGoal2HorizontalAnimals($grid);
        $equal = $result == 6;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalSeveralAnimalsTouchingOtter() {
        $grid = $this->initGrid();
        //according to rule exemple
        $grid[0][2] = new Biome(ANIMAL_PINGUIN);

        $grid[1][0] = new Biome(ANIMAL_LION);
        $grid[1][1] = new Biome(ANIMAL_OTTER, LAND_SNOW);
        $grid[1][2] =  new Biome(ANIMAL_PINGUIN);

        $grid[2][0] = new Biome(ANIMAL_LION);
        $grid[2][1] =  new Biome(ANIMAL_PINGUIN);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalSeveralAnimalsTouchingOtter($grid);
        $equal = $result == 2;
        $this->displayResult(__FUNCTION__, $equal, $result);
    }

    function testCalculateGoalSeveralAnimalsTouchingOtterComplex() {
        $grid = $this->initGrid();
        $grid[0][3] = new Biome(ANIMAL_GORILLA);
        $grid[0][4] = new Biome(ANIMAL_OTTER, LAND_SNOW);

        $grid[1][0] = new Biome(ANIMAL_GIRAFFE);
        $grid[1][1] =  new Biome(ANIMAL_FLAMINGO);
        $grid[1][2] =  new Biome(ANIMAL_GORILLA);
        $grid[1][3] = new Biome(ANIMAL_OTTER, LAND_SNOW);
        $grid[1][4] =  new Biome(ANIMAL_PANDA);
        $grid[1][5] =  new Biome(ANIMAL_GORILLA);

        $grid[2][0] = new Biome(ANIMAL_FLAMINGO);
        $grid[2][1] =  new Biome(ANIMAL_LION);
        $grid[2][2] =  new Biome(ANIMAL_PINGUIN);
        $grid[2][3] = new Biome(ANIMAL_PANDA);
        $grid[2][4] =  new Biome(ANIMAL_CROCODILE);
        $grid[2][5] =  new Biome(ANIMAL_PINGUIN);

        $grid[3][1] =  new Biome(ANIMAL_PANDA);
        $grid[3][2] =  new Biome(ANIMAL_FLAMINGO);
        $grid[3][3] = new Biome(ANIMAL_BEAR);
        $grid[3][4] =  new Biome(ANIMAL_OTTER, LAND_SNOW);
        $grid[3][5] =  new Biome(ANIMAL_FLAMINGO);

        $grid[4][0] = new Biome(ANIMAL_PANDA);
        $grid[4][1] =  new Biome(ANIMAL_FLAMINGO);
        $grid[4][2] =  new Biome(ANIMAL_CROCODILE);
        $grid[4][3] = new Biome(ANIMAL_OTTER, LAND_SNOW);
        $grid[4][4] =  new Biome(ANIMAL_BEAR);
        $grid[4][5] =  new Biome(ANIMAL_CROCODILE);

        $grid[5][0] = new Biome(ANIMAL_GORILLA);
        $grid[5][1] =  new Biome(ANIMAL_GIRAFFE);
        $grid[5][2] =  new Biome(ANIMAL_FLAMINGO);
        $grid[5][3] = new Biome(ANIMAL_GORILLA);
        $grid[5][4] =  new Biome(ANIMAL_CROCODILE);
        $grid[5][5] =  new Biome(ANIMAL_PANDA);
        //$this->displayGrid($grid);

        $result = $this->calculateGoalSeveralAnimalsTouchingOtter($grid);
        $equal = $result == 8;
        $this->displayResult(__FUNCTION__, $equal, $result);
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
        $this->displayGrid($grid);
        return $grid;
    }

    function testAll() {
        $this->testcalculateGoalAnimalTouchingAtLeastOneKindOfAnimal();
        $this->testcalculateGoalAnimalTouchingAtLeastOneLand();
        $this->testCalculateGoalLeastLionsFalse();
        $this->testCalculateGoalLeastLionsTrue();
        $this->testCalculateGoalExactlyOneAnimalOfTypePerColonne();
        $this->testCalculateLandZonesNone();
        $this->testCalculateLandZonesOne();
        $this->testCalculateLandZonesRuleCase();
        $this->testCalculateGoalCompleteSquareAnywhere();
        $this->testCalculateGoalCompleteSquare21();
        $this->testCalculateGoalCompleteSquare4();
        $this->testCalculateGoalCompleteSquareWithHole();
        $this->testCalculateGoalAnimalTouchingBorder();
        $this->testCalculateGoalAnimalNotTouchingBorder();
        $this->testCalculateGoalLineWithAllLands();
        $this->testCalculateGoalSmallestLandSquares();
        $this->testCalculateGoal2x2Squares();
        $this->testCalculateLargestRiverOneLongContinous();
        $this->testCalculateLargestRiverOneWithLoop();
        $this->testCalculateLargestRiver3Rivers();
        $this->testCalculateGoalLandZonesCount();
        $this->testCalculateGoalAnimalZonesCount();
        $this->testCalculateGoalBiggestSavannah();
        $this->testCalculateGoal2HorizontalAnimals();
        $this->testCalculateGoal2HorizontalAnimalsWithOneUnder();
        $this->testCalculateGoal2HorizontalAnimalsWith2OthersUnder();

        $this->testCalculateGoalSeveralAnimalsTouchingOtter();
        $this->testCalculateGoalSeveralAnimalsTouchingOtterComplex();
        $this->testCalculateGoalRiverConnectedToLand();
        $this->testCalculateGoalRiverConnectedToLandHorizontaly();
        $this->testBiomesCorrectDescription();
        $this->testCalculateLargestRiverOneLongContinousLength7();
        $this->testCalculateLargestRiverOf2();
        $this->testCalculateLargestSnakeRiver();
        $this->testGetRotatedBiomesAndRivers90Degrees();
        $this->testGetRotatedBiomesAndRivers180Degrees();
        $this->testGetRotatedBiomesAndRivers270Degrees();
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
}

$test1 = new GameTest();
$test1->testAll();
