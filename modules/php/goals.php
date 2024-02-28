<?php

require_once(__DIR__ . '/objects/Goal.php');
trait GoalTrait {

    /** 
     * Choose the goals used for the entire game, at the beginning of it, according to level options. 
     * Easy includes easy and medium cards. 
     * Difficult includes medium and difficult cards
     */
    public function selectGoals() {
        $goals = [];
        $excluded = []; //goals are recto-verso, if you choose one side, you can’t have the other
        foreach (GOAL_COLORS as $color) {
            $matchingGoals = array_values(array_filter($this->GOALS, fn ($g) => $g->color === $color && !in_array($g->id, $excluded)));
            $level  = intval($this->getGameStateValue(GOAL_LEVEL));
            if ($level != LEVEL_RANDOM) {
                $matchingGoals = array_values(array_filter($matchingGoals, fn ($g) => $g->level === $level || $g->level === $level + 1));
            }
            $randIndex = bga_rand(0, count($matchingGoals) - 1);
            $goal = $matchingGoals[$randIndex];
            $excluded[] = $goal->id % 2 == 1 ? $goal->id + 1 : $goal->id - 1; //odd prevents the next goal, even the previous
            $goals[] = $goal;
        }
        if (count($goals) != 4) throw new BgaVisibleSystemException("Impossible to setup goals correctly" . json_encode($goals), 555);
        $this->setGlobalVariable("GOALS", $goals);
    }

    /** Returns only goals that applies to the current round. */
    public function getRoundGoals($round = null) {
        if (!$round) {
            $round = intval(self::getGameStateValue(ROUND));
        }
        $goals = $this->getGlobalVariable("GOALS", true);
        switch ($round) {
            case 1:
                return $this->filterColorGoals($goals, [GOAL_BLUE, GOAL_GREEN], "round1GoalComparator");
            case 2:
                return $this->filterColorGoals($goals, [GOAL_YELLOW, GOAL_GREEN], "round2GoalComparator");
            case 3:
                return $this->filterColorGoals($goals, [GOAL_BLUE, GOAL_RED], "round3GoalComparator");
            case 4:
                return $this->filterColorGoals($goals, [GOAL_GREEN, GOAL_YELLOW, GOAL_RED], "round4GoalComparator");
            case 5:
                return $this->filterColorGoals($goals, [GOAL_BLUE, GOAL_YELLOW, GOAL_RED], "round5GoalComparator");

            default:
                throw new BgaVisibleSystemException("this round is never supposed to happen : " . $round);
        }
    }

    function round1GoalComparator($goal1, $goal2) {
        return $this->compareGoals($goal1, $goal2, [GOAL_BLUE, GOAL_GREEN]);
    }

    function round2GoalComparator($goal1, $goal2) {
        return $this->compareGoals($goal1, $goal2, [GOAL_GREEN, GOAL_YELLOW]);
    }

    function round3GoalComparator($goal1, $goal2) {
        return $this->compareGoals($goal1, $goal2, [GOAL_BLUE, GOAL_RED]);
    }

    function round4GoalComparator($goal1, $goal2) {
        return $this->compareGoals($goal1, $goal2, [GOAL_GREEN, GOAL_YELLOW, GOAL_RED]);
    }

    function round5GoalComparator($goal1, $goal2) {
        return $this->compareGoals($goal1, $goal2, [GOAL_BLUE, GOAL_RED, GOAL_YELLOW]);
    }

    function compareGoals($goal1, $goal2, $expectedOrder) {
        $colorIndex1 = array_search($goal1->color, $expectedOrder);
        $colorIndex2 = array_search($goal2->color, $expectedOrder);
        return $colorIndex1 - $colorIndex2;
    }

    private function filterColorGoals(array $goals, array $colors, $roundGoalComparator) {
        $goals = array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => in_array($g["color"], $colors))));
        usort($goals, array($this, $roundGoalComparator));
        return $goals;
    }

    public function getGameGoals() {
        $goals = $this->getGlobalVariable("GOALS", true);
        return array_map(fn ($g) => Goal̤::getCastedGoal($g), $goals);
    }

    /** Calculates points for a given goal and a given player. Called at the end of each round. */
    public function calculateGoalPoints(Goal̤ $goal, $playerId) {
        $grid = $this->getGrid($playerId);
        switch ($goal->id) {
            case 1:
                return $this->calculateGoalSeveralAnimalsTouchingOtter($grid);
            case 2:
                return $this->calculateGoalRiverConnectedToLand($grid, LAND_WATER);
            case 3:
                return $this->calculateGoalLandZonesCount($grid, LAND_JUNGLE);
            case 4:
                return $this->calculateGoalAnimalTouchingAtLeastOneKindOfLand($grid, ANIMAL_GORILLA, LAND_WATER);
            case 5:
                return $this->calculateGoalBiggestSavannah($grid);
            case 6:
                return $this->calculateGoalLandZonesSize4($grid, LAND_SAVANNAH);
            case 7:
                return $this->calculateGoal2x2Squares($grid, LAND_SNOW);
            case 8:
                return $this->calculateGoalAnimalZonesCount($grid, ANIMAL_BEAR);
            case 9:
                return $this->calculateGoalSmallestLandSquares($grid);
            case 10:
                return $this->calculateGoalCompleteSquare($grid);
            case 11:
                return $this->calculateGoalLineWithAllLands($grid);
            case 12:
                return $this->calculateGoal2HorizontalAnimals($grid);
            case 13:
                $giraffeCountPoints = [8, 5, 3, 0];
                return $giraffeCountPoints[min(3, $this->countAnimals($grid, ANIMAL_GIRAFFE))];
            case 14:
                return $this->calculateGoalAnimalTouchingBorder($grid, ANIMAL_FLAMINGO, false);
            case 15:
                return $this->calculateGoalMyLongestRiver($grid);
            case 16:
                return $this->calculateGoalLongestRiverAmongPlayers($playerId, 5, 2);
            case 17:
                return $this->calculateGoalAnimalTouchingBorder($grid, ANIMAL_PANDA, true);
            case 18:
                $gorilla = $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_GORILLA, false, 5, 2);
                $panda = $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_PANDA, false, -5, -2);
                self::notifyAllPlayers("msg", clienttranslate('${player_name} scores ${count} points for gorillas'), ['player_name' => $this->getPlayerName($playerId), 'count' => $gorilla]);
                self::notifyAllPlayers("msg", clienttranslate('${player_name} scores ${count} points for pandas'), ['player_name' => $this->getPlayerName($playerId), 'count' => $panda]);
                return $gorilla + $panda;
            case 19:
                return $this->calculateGoalExactlyOneAnimalOfTypePerColonne($grid, ANIMAL_PINGUIN);
            case 20:
                return $this->calculateGoalLeastLions($playerId);
            case 21:
                $crocodile = $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_CROCODILE, false, 5, 2);
                $flamingo = $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_FLAMINGO, true, 5, 2);
                self::notifyAllPlayers("msg", clienttranslate('${player_name} scores ${count} points for crocodiles'), ['player_name' => $this->getPlayerName($playerId), 'count' => $crocodile]);
                self::notifyAllPlayers("msg", clienttranslate('${player_name} scores ${count} points for flamingos'), ['player_name' => $this->getPlayerName($playerId), 'count' => $flamingo]);
                return $crocodile + $flamingo;
            case 22:
                return $this->calculateGoalAnimalTouchingAtLeastOneOtherAnimal($grid, ANIMAL_CROCODILE, ANIMAL_GIRAFFE);

            default:
                # code...
                break;
        }
        return 0;
    }

    function getGrid($playerId) {
        $grid = [];
        $fakeBiome = new Biome(0, FAKE_LAND, 0); //to avoid null errors
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $grid[] = array_fill(0, GRID_SIZE, $fakeBiome);
        }

        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("grid$playerId", null, "card_order_in_grid"));
        //row first, then col
        foreach ($cards as $card) {
            $biomes = $this->getRotatedBiomesAndRivers($card);
            $anchorCol = $card->location_arg % GRID_SIZE - 1; //array starting at 0
            $anchorRow = ceil($card->location_arg / GRID_SIZE) - 1; //same
            //anchor is top left part of the card
            $grid[$anchorRow][$anchorCol] = $biomes[0];
            $grid[$anchorRow][$anchorCol + 1] = $biomes[1];
            $grid[$anchorRow + 1][$anchorCol] = $biomes[2];
            $grid[$anchorRow + 1][$anchorCol + 1] = $biomes[3];
        }
        //$this->displayGrid($grid);
        return $grid;
    }

    function displayGrid(array $grid) {
        $h = count($grid);
        $w = count($grid[0]);
        $str = "grid  $w x $h|\n";
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $row = '';
            for ($j = 0; $j < GRID_SIZE; $j++) {
                if (isset($grid[$i][$j])) {
                    $val = $grid[$i][$j]->animal;
                } else {
                    $val = '0';
                }
                $row .= $val;
            }
            $str .= $row . "\n";
        }
        self::dump('*******************grid', $str);
        return $str;
    }

    function displayPlayerGrid() {
        return $this->displayGrid($this->getGrid(self::getCurrentPlayerId()));
    }

    function displayRiverGrid(array $grid) {
        $h = count($grid);
        $w = count($grid[0]);
        $str = "grid  $w x $h|\n";
        for ($i = 0; $i < GRID_SIZE; $i++) {
            $row = '';
            for ($j = 0; $j < GRID_SIZE; $j++) {
                if (isset($grid[$i][$j]) && $grid[$i][$j]->river != 0) {
                    if ($grid[$i][$j]->river == RIVER_UP) {
                        $val = "/";
                    } else if ($grid[$i][$j]->river == RIVER_DOWN) {
                        $val = "\\";
                    }
                } else {
                    $val = '0';
                }
                $row .= $val;
            }
            $str .= $row . "\n";
        }
        self::dump('*******************grid', $str);
    }

    function getRotatedBiomes(BiomeCard $card) {
        switch ($card->rotation) {
            case 0:
                return $card->biomes;
            case 90:
                return [$card->biomes[2], $card->biomes[0], $card->biomes[3], $card->biomes[1]];
            case 180:
                return [$card->biomes[3], $card->biomes[2], $card->biomes[1], $card->biomes[0]];
            case 270:
                return [$card->biomes[1], $card->biomes[3], $card->biomes[0], $card->biomes[2]];
        }
    }

    function getRotatedBiomesAndRivers(BiomeCard $card) {
        $biomes = $this->getRotatedBiomes($card);
        foreach ($biomes as &$biome) {
            if ($biome->river == RIVER_UP || $biome->river == RIVER_DOWN)
                switch ($card->rotation) {
                    case 90:
                        $this->rotateRiver($biome);
                        break;
                    case 270:
                        $this->rotateRiver($biome);
                        break;
                }
        }
        return $biomes;
    }

    function rotateRiver(Biome &$biome) {
        if ($biome->river == RIVER_UP) {
            $biome->river = RIVER_DOWN;
        } else {
            $biome->river = RIVER_UP;
        }
    }

    function countAnimals(array $grid, $animal) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains an expected animal
                if ($grid[$row][$col]->animal == $animal) {
                    $points++;
                }
            }
        }

        return $points;
    }

    function calculateGoalAnimalTouchingAtLeastOneOtherAnimal(array $grid, int $animal, int $otherAnimal) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains an expected animal
                if ($grid[$row][$col]->animal == $animal) {
                    // Check adjacent cells (orthogonally)
                    if (
                        $otherAnimal && $row > 0 && $grid[$row - 1][$col]->animal == $otherAnimal ||
                        $otherAnimal && $row < $gridSize - 1 && $grid[$row + 1][$col]->animal == $otherAnimal
                        || $otherAnimal && $col > 0 && $grid[$row][$col - 1]->animal == $otherAnimal
                        || $otherAnimal && $col < count($grid[$row]) - 1 && $grid[$row][$col + 1]->animal == $otherAnimal
                    ) {
                        $points += 2;
                    }
                }
            }
        }

        return $points;
    }

    function getSeveralAnimalsTouchingOtter(array $grid, int $otter = ANIMAL_OTTER) {
        $animalInfo = array_fill(1, 9, array()); // Initialize the array for each animal type
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains any animal
                $currentAnimal = $grid[$row][$col]->animal;
                if ($currentAnimal == $otter) {
                    // Check adjacent cells (orthogonally)
                    if ($row > 0 && $grid[$row - 1][$col]->animal !== 0) {
                        $animalType = $grid[$row - 1][$col]->animal;
                        $animalInfo[$animalType][] = ['row' => $row - 1, 'col' => $col];
                    }

                    if ($row < $gridSize - 1 && $grid[$row + 1][$col]->animal !== 0) {
                        $animalType = $grid[$row + 1][$col]->animal;
                        $animalInfo[$animalType][] = ['row' => $row + 1, 'col' => $col];
                    }

                    if ($col > 0 && $grid[$row][$col - 1]->animal !== 0) {
                        $animalType = $grid[$row][$col - 1]->animal;
                        $animalInfo[$animalType][] = ['row' => $row, 'col' => $col - 1];
                    }

                    if ($col < count($grid[$row]) - 1 && $grid[$row][$col + 1]->animal !== 0) {
                        $animalType = $grid[$row][$col + 1]->animal;
                        $animalInfo[$animalType][] = ['row' => $row, 'col' => $col + 1];
                    }
                }
            }
        }
        return $animalInfo;
    }

    function calculateGoalSeveralAnimalsTouchingOtter(array $grid) {
        $squaresTouching = $this->getSeveralAnimalsTouchingOtter($grid);
        $severalCopies = array_filter($squaresTouching, fn ($squares) => count($squares) > 1);
        return array_reduce(
            $severalCopies,
            fn ($carry, $zone) => $carry + (count($zone)),
            0
        );
    }

    function calculateGoalAnimalTouchingAtLeastOneKindOfLand(array $grid, int $animal, int $landType) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains an expected animal
                if ($grid[$row][$col]->animal == $animal) {
                    // Check adjacent cells (orthogonally)
                    if (
                        $landType && $row > 0 && $grid[$row - 1][$col]->land == $landType ||
                        $landType && $row < $gridSize - 1 && $grid[$row + 1][$col]->land == $landType
                        || $landType && $col > 0 && $grid[$row][$col - 1]->land == $landType
                        || $landType && $col < count($grid[$row]) - 1 && $grid[$row][$col + 1]->land == $landType
                    ) {
                        $points += 2;
                    }
                }
            }
        }

        return $points;
    }

    function calculateGoalLeastLions($currentPlayerId) {
        $players = $this->getPlayersIds();
        $lions = [];
        foreach ($players as $playerId) {
            $lions[$playerId] = $this->countAnimals($this->getGrid($playerId), ANIMAL_LION);
        }
        return min($lions) == $lions[$currentPlayerId] ? 3 : -2;
    }

    function calculateGoalRelativeAnimalsCount($currentPlayerId, int $animal, bool $min, int $winnerPoints, int $secondPoints) {
        $players = $this->getPlayersIds();
        $animalCount = [];
        foreach ($players as $playerId) {
            $animalCount[$playerId] = $this->countAnimals($this->getGrid($playerId), $animal);
        }
        $minCount = min($animalCount);
        $maxCount = max($animalCount);
        $winnerExpectedValue = $min ? $minCount : $maxCount;

        if (count($players) == 3 || count($players) == 4) {
            $animalCountCopy = $animalCount;
            if ($min) {
                sort($animalCountCopy);
            } else {
                rsort($animalCountCopy);
            }
            if (count($animalCountCopy) > 1) {
                $second =  $animalCount[$currentPlayerId] == $animalCountCopy[1];
            }

            $tieForFirst = count(array_filter($animalCount, fn ($nb) => $nb == $winnerExpectedValue)) > 1;
            if (!$tieForFirst && $second) {
                return $secondPoints;
            }
        }
        return $winnerExpectedValue == $animalCount[$currentPlayerId] ? $winnerPoints : 0;
    }

    function calculateGoalLongestRiverAmongPlayers($currentPlayerId, int $winnerPoints, int $secondPoints) {
        $players = $this->getPlayersIds();
        $size = [];
        foreach ($players as $playerId) {
            $size[$playerId] = $this->calculateLargestRiver($this->getGrid($playerId));
        }
        if (count($players) == 3 || count($players) == 4) {
            $sizeCopy = $size;
            sort($sizeCopy);
            if (count($sizeCopy) > 1) {
                $second =  $size[$currentPlayerId] == $sizeCopy[1];
            }
            $tieForFirst = count(array_filter($size, fn ($nb) => $nb ==  max($size)));
            if (!$tieForFirst && $second) {
                return $secondPoints;
            }
        }
        return (max($size)) == $size[$currentPlayerId] ? $winnerPoints : 0;
    }

    function calculateGoalExactlyOneAnimalOfTypePerColonne(array $grid, int $animal) {
        $points = 0;
        $numColumns = count($grid[0]);

        for ($column = 0; $column < $numColumns; $column++) {
            $numPenguinsInColumn = 0;

            foreach ($grid as $row) {
                // Check if the cell contains a penguin
                if ($row[$column]->animal == $animal) {
                    $numPenguinsInColumn++;
                }
            }

            if ($numPenguinsInColumn === 1) {
                $points += 3;
            }
        }
        return $points;
    }

    function exploreLandZone(array $grid, &$visited, $i, $j, $rows, $cols, &$zone, int $land) {
        if ($i < 0 || $i >= $rows || $j < 0 || $j >= $cols || $visited[$i][$j] || $grid[$i][$j]->land != $land)
            return;

        $visited[$i][$j] = true;
        $zone[] = [$i, $j];

        // Explorer les cases voisines (à droite, en bas, en haut, à gauche)
        $this->exploreLandZone($grid, $visited, $i, $j + 1, $rows, $cols, $zone, $land);
        $this->exploreLandZone($grid, $visited, $i + 1, $j, $rows, $cols, $zone, $land);
        $this->exploreLandZone($grid, $visited, $i - 1, $j, $rows, $cols, $zone, $land);
        $this->exploreLandZone($grid, $visited, $i, $j - 1, $rows, $cols, $zone, $land);
    }

    function exploreAnimalZone(array $grid, &$visited, $i, $j, $rows, $cols, &$zone, int $animal) {
        if ($i < 0 || $i >= $rows || $j < 0 || $j >= $cols || $visited[$i][$j] || $grid[$i][$j]->animal != $animal)
            return;

        $visited[$i][$j] = true;
        $zone[] = [$i, $j];

        // Explorer les cases voisines (à droite, en bas, en haut, à gauche)
        $this->exploreAnimalZone($grid, $visited, $i, $j + 1, $rows, $cols, $zone, $animal);
        $this->exploreAnimalZone($grid, $visited, $i + 1, $j, $rows, $cols, $zone, $animal);
        $this->exploreAnimalZone($grid, $visited, $i - 1, $j, $rows, $cols, $zone, $animal);
        $this->exploreAnimalZone($grid, $visited, $i, $j - 1, $rows, $cols, $zone, $animal);
    }

    function calculateGoalLandZonesSize4(array $grid, int $land) {
        $count = 0;
        $rows = count($grid);
        $cols = count($grid[0]);
        $visited = array_fill(0, $rows, array_fill(0, $cols, false));

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($grid[$i][$j]->land == $land && !$visited[$i][$j]) {
                    $zone = [];
                    $this->exploreLandZone($grid, $visited, $i, $j, $rows, $cols, $zone, $land);
                    if (count($zone) === 4) {
                        $count++;
                    }
                }
            }
        }

        return $count * 6;
    }

    function calculateGoalLandZonesCount(array $grid, int $land) {
        $distinctZones = $this->calculateLandZones($grid, $land);
        return count($distinctZones) * 2;
    }

    function calculateLandZones(array $grid, int $land) {
        $rows = count($grid);
        $cols = count($grid[0]);
        $visited = array_fill(0, $rows, array_fill(0, $cols, false));
        $distinctZones = [];

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($grid[$i][$j]->land == $land && !$visited[$i][$j]) {
                    $zone = [];
                    $this->exploreLandZone($grid, $visited, $i, $j, $rows, $cols, $zone, $land);

                    if (!empty($zone)) {
                        $distinctZones[] = $zone;
                    }
                }
            }
        }
        return $distinctZones;
    }

    function calculateAnimalZones(array $grid, int $animal) {
        $rows = count($grid);
        $cols = count($grid[0]);
        $visited = array_fill(0, $rows, array_fill(0, $cols, false));
        $distinctZones = [];

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($grid[$i][$j]->animal == $animal && !$visited[$i][$j]) {
                    $zone = [];
                    $this->exploreAnimalZone($grid, $visited, $i, $j, $rows, $cols, $zone, $animal);

                    if (!empty($zone)) {
                        $distinctZones[] = $zone;
                    }
                }
            }
        }
        return $distinctZones;
    }

    function calculateGoalAnimalZonesCount(array $grid, int $animal) {
        $distinctZones = $this->calculateAnimalZones($grid, $animal);
        return array_reduce(
            $distinctZones,
            fn ($carry, $zone) => $carry + (count($zone) === 1 ? -1 : count($zone) * 2),
            0
        );
    }

    function calculateGoalBiggestSavannah(array $grid) {
        $distinctZones = $this->calculateLandZones($grid, LAND_SAVANNAH);
        $sizeMax = max(array_map(fn ($zone) => count($zone), $distinctZones));
        return $sizeMax * 2;
    }

    function calculateGoal2HorizontalAnimals(array $grid) {
        $allZones = [];
        foreach (ANIMALS as  $animal) {
            $allZones = array_merge($allZones, array_values($this->calculateAnimalZones($grid, $animal)));
        }
        //only zones whose row number is the same
        $matchingZones = array_filter($allZones, fn ($zone) => count(array_unique(array_map(fn ($square) => $square[0], $zone))) == 1 && count($zone) == 2);
        return count($matchingZones) * 3;
    }

    function calculateGoalCompleteSquare(array $grid) {
        $maxSquareSize = 0;

        for ($startRow = 0; $startRow < GRID_SIZE; $startRow++) {
            for ($startCol = 0; $startCol < GRID_SIZE; $startCol++) {
                $squareSize = 0;
                $foundEmptySquare = false;
    
                while ($startRow + $squareSize < GRID_SIZE &&
                       $startCol + $squareSize < GRID_SIZE &&
                       !$foundEmptySquare) {
                    for ($i = $startRow + $squareSize; $i >= $startRow && !$foundEmptySquare; $i--) {
                        for ($j = $startCol + $squareSize; $j >= $startCol && !$foundEmptySquare; $j--) {
                            $foundEmptySquare = $foundEmptySquare || $grid[$i][$j]->land == FAKE_LAND;
                        }
                    }
    
                    if (!$foundEmptySquare) {
                        $squareSize++;
                    }
                }
    
                $maxSquareSize = max($maxSquareSize, $squareSize);
            }
        }
    
        $points = [0, 0, 3, 5, 8, 13, 21];
        return $points[$maxSquareSize];
    }

    function calculateGoalAnimalTouchingBorder(array $grid, int $animal, bool $touching) {
        $points = 0;
        $gridSize = count($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell contains an expected animal
                if ($grid[$row][$col]->animal == $animal) {
                    // Check adjacent cells (orthogonally) for indexes outside of the grid or fake land
                    $touchingBorder = $row - 1 < 0 || $grid[$row - 1][$col]->land == FAKE_LAND
                        || $row + 1 > $gridSize - 1 ||  $grid[$row + 1][$col]->land == FAKE_LAND
                        || $col - 1 < 0 || $grid[$row][$col - 1]->land == FAKE_LAND
                        || $col - 1 < 0 || $col + 1 > $gridSize - 1 || $col < count($grid[$row]) - 1 && $grid[$row][$col + 1]->land == FAKE_LAND;

                    if ($touching && $touchingBorder || !$touching && !$touchingBorder) {
                        $points += 3;
                    }
                }
            }
        }
        return $points;
    }

    function calculateGoalLineWithAllLands(array $grid) {
        $points = 0;
        $rows = count($grid);
        $cols = count($grid[0]);

        // Iterate through each row of the grid
        for ($i = 0; $i < $rows; $i++) {
            $lands = array(LAND_SNOW => false, LAND_WATER => false, LAND_JUNGLE => false, LAND_SAVANNAH => false);

            //Iterate through each column of the row
            for ($j = 0; $j < $cols; $j++) {
                //Mark the terrain type of the current cell as present
                if ($grid[$i][$j]->land != FAKE_LAND)
                    $lands[$grid[$i][$j]->land] = true;
            }

            //Check if all terrain types are present in the row
            if (array_reduce($lands, function ($carry, $value) {
                return $carry && $value;
            }, true)) {
                $points += 3;
            }
        }

        return $points;
    }

    function calculateGoalSmallestLandSquares(array $grid) {
        $rows = count($grid);
        $cols = count($grid[0]);

        // Iterate through each row of the grid
        $lands = array(LAND_SNOW => 0, LAND_WATER => 0, LAND_JUNGLE => 0, LAND_SAVANNAH => 0);
        for ($i = 0; $i < $rows; $i++) {

            //Iterate through each column of the row
            for ($j = 0; $j < $cols; $j++) {
                //count land
                if ($grid[$i][$j]->land != FAKE_LAND) {
                    $lands[$grid[$i][$j]->land]++;
                }
            }
        }

        return min($lands) * 2;
    }

    function calculateGoal2x2Squares(array $grid, int $land) {
        $count = 0;
        $rows = count($grid);
        $cols = count($grid[0]);

        // Iterate through each cell except the last row and last column
        for ($i = 0; $i < $rows - 1; $i++) {
            for ($j = 0; $j < $cols - 1; $j++) {
                // Check if the current 2x2 square is composed of the expected land
                if (
                    $grid[$i][$j]->land == $land &&
                    $grid[$i][$j + 1]->land == $land &&
                    $grid[$i + 1][$j]->land == $land &&
                    $grid[$i + 1][$j + 1]->land == $land
                ) {
                    $count++;
                }
            }
        }

        return $count * 4;
    }

    function calculateGoalRiverConnectedToLand(array $grid, int $landType) {
        $points = 0;
        $gridSize = count($grid);
        //$this->displayGrid($grid);
        //$this->displayRiverGrid($grid);

        // Iterate through each row of the grid
        for ($row = 0; $row < $gridSize; $row++) {
            // Iterate through each column of the grid
            for ($col = 0; $col < count($grid[$row]); $col++) {
                // Check if the cell has an otter
                if ($grid[$row][$col]->animal == ANIMAL_OTTER) {
                    //self::dump('*******************Otter in ', compact("row", "col"));
                    // Check adjacent and diagonal cells according to river direction
                    $direction = $grid[$row][$col]->river;
                    // Define the coordinates of cells touching the river
                    $touchingCoordinates = [];
                    ///self::dump('*******************direction', $direction);

                    // Check if river goes up
                    if ($direction == RIVER_UP) {
                        $touchingCoordinates = [
                            [$row + 1, $col], //down
                            [$row + 1, $col - 1], //bottom left diag
                            [$row, $col - 1], //left
                            [$row - 1, $col + 1], //top right diag
                            [$row, $col + 1], //right
                            [$row - 1, $col], //top
                        ];
                    }
                    // Check if river goes down
                    else if ($direction == RIVER_DOWN) {
                        $touchingCoordinates = [
                            [$row - 1, $col],         // up
                            [$row - 1, $col - 1],     // top left diag
                            [$row, $col - 1],         // left
                            [$row + 1, $col + 1],     // bottom right diag
                            [$row, $col + 1],         // right
                            [$row + 1, $col]          // bottom
                        ];
                    }

                    // Check if any of the touching cells have the specified land type
                    if (array_reduce($touchingCoordinates, function ($carry, $coord) use ($grid, $gridSize, $landType) {
                        list($r, $c) = $coord;
                        //self::dump('*******************$coord', $coord);
                        // self::dump('*******************res', $carry || ($r >= 0 && $r < $gridSize && $c >= 0 && $c < count($grid[$r]) && $grid[$r][$c]->land == $landType));
                        return $carry || ($r >= 0 && $r < $gridSize && $c >= 0 && $c < count($grid[$r]) && $grid[$r][$c]->land == $landType);
                    }, false)) {
                        //self::dump('*******************found', compact("row", "col"));
                        $points += 2;
                    }
                }
            }
        }

        return $points;
    }

    function exploreRiver($biomes, &$rivers, $i, $j, $rows, $cols, $direction) {

        if ($i < 0 || $i >= $rows || $j < 0 || $j >= $cols || $rivers[$i][$j]['visited'] || $biomes[$i][$j]->animal !== ANIMAL_OTTER) {
            return 0;
        }

        $rivers[$i][$j]['visited'] = true;
        $size = 1;

        //self::dump('***********exploreRiver********',compact("i", "j","direction"));
        // Explore the river only in the directions that are continuous
        if ($direction === RIVER_UP) {
            $size += $this->exploreRiver($biomes, $rivers, $i - 1, $j, $rows, $cols, $direction); // Up
            $size += $this->exploreRiver($biomes, $rivers, $i, $j + 1, $rows, $cols, RIVER_DOWN); // Right
            $size += $this->exploreRiver($biomes, $rivers, $i - 1, $j + 1, $rows, $cols, RIVER_UP); // Diagonal Up-Right

            $size += $this->exploreRiver($biomes, $rivers, $i + 1, $j, $rows, $cols, RIVER_DOWN); // Down
            $size += $this->exploreRiver($biomes, $rivers, $i, $j - 1, $rows, $cols, RIVER_DOWN); // Left
            $size += $this->exploreRiver($biomes, $rivers, $i + 1, $j - 1, $rows, $cols, RIVER_UP); // Diagonal Down-Left
        } elseif ($direction === RIVER_DOWN) {
            $size += $this->exploreRiver($biomes, $rivers, $i + 1, $j, $rows, $cols, RIVER_UP); // Down
            $size += $this->exploreRiver($biomes, $rivers, $i, $j + 1, $rows, $cols, RIVER_UP); // Right
            $size += $this->exploreRiver($biomes, $rivers, $i + 1, $j + 1, $rows, $cols, RIVER_DOWN); // Diagonal Down-Right

            $size += $this->exploreRiver($biomes, $rivers, $i - 1, $j, $rows, $cols, RIVER_UP); // Up
            $size += $this->exploreRiver($biomes, $rivers, $i, $j - 1, $rows, $cols, RIVER_UP); // Left
            $size += $this->exploreRiver($biomes, $rivers, $i - 1, $j - 1, $rows, $cols, RIVER_DOWN); // Diagonal Up-Left
        }

        return $size;
    }

    /**
     * River length => points
     * 0 => 0
     * 1 => 0
     * 2 => 1
     * 3 => 3
     * 4 => 6
     * 5 => 10
     * 6 => 15
     */
    function calculateLargestRiver($biomes) {
        $rows = count($biomes);
        $cols = count($biomes[0]);

        // Initialize an array to track the visited status of each river cell
        $rivers = array_fill(0, $rows, array_fill(0, $cols, array('visited' => false)));

        $maxRiverSize = 0;

        // Iterate through each cell to find the largest river
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($biomes[$i][$j]->river !== 0 && !$rivers[$i][$j]['visited']) {
                    // Explore the river starting from the current cell in the specified direction
                    $riverSize = $this->exploreRiver($biomes, $rivers, $i, $j, $rows, $cols, $biomes[$i][$j]->river);
                    //self::dump('***********calculateLargestRiver********',compact("i", "j","riverSize"));

                    // Update the maximum river size
                    $maxRiverSize = max($maxRiverSize, $riverSize);
                }
            }
        }

        return $maxRiverSize;
    }

    function calculateGoalMyLongestRiver(array $grid) {
        $size = $this->calculateLargestRiver($grid);
        $points = 0;
        for ($i = 0; $i < $size; $i++) {
            $points += $i;
        }
        return min(15, $points);
    }
}
