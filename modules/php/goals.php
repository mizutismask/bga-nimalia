<?php

require_once(__DIR__ . '/objects/Goal.php');
trait GoalTrait {

    /** Choose the goals used for the entire game, at the beginning of it, according to level options. */
    public function selectGoals() {
        $goals = [];
        foreach (GOAL_COLORS as $color) {
            $matchingGoals = array_values(array_filter($this->GOALS, fn ($g) => $g->color === $color));
            $level  = intval($this->getGameStateValue(GOAL_LEVEL));
            if ($level != LEVEL_RANDOM) {
                $matchingGoals = array_values(array_filter($matchingGoals, fn ($g) => $g->level === $level));
            }
            $randIndex = bga_rand(0, count($matchingGoals) - 1);
            $goals[] = $matchingGoals[$randIndex];
        }
        $this->setGlobalVariable("GOALS", $goals);
    }

    /** Returns only goals that applies to the current round. */
    public function getRoundGoals() {
        $round = intval(self::getGameStateValue(ROUND));
        $goals = $this->getGlobalVariable("GOALS", true);
        switch ($round) {
            case 1:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_GREEN)));
            case 2:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_GREEN)));
            case 3:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_RED)));
            case 4:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_GREEN || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED)));
            case 5:
                return array_map(fn ($o) => Goal̤::getCastedGoal($o), array_values(array_filter($goals, fn ($g) => $g["color"] == GOAL_BLUE || $g["color"] == GOAL_YELLOW || $g["color"] == GOAL_RED)));

            default:
                throw new BgaVisibleSystemException("this round is never supposed to happen : " . $round);
        }
    }

    /** Calculates points for a given goal and a given player. Called at the end of each round. */
    public function calculateGoalPoints(Goal̤ $goal, $playerId) {
        $grid = $this->getGrid($playerId);
        switch ($goal->id) {
            case 22:
                return $this->calculateGoalAnimalTouchingAtLeastOneOtherAnimal($grid, $playerId, ANIMAL_CROCODILE, ANIMAL_GIRAFFE, null);
            case 4:
                return $this->calculateGoalAnimalTouchingAtLeastOneOtherAnimal($grid, $playerId, ANIMAL_GORILLA, null, LAND_WATER);
            case 20:
                return $this->calculateGoalLeastLions($playerId);
            case 6:
                return $this->calculateLandZones($grid, $playerId);
            case 19:
                return $this->calculateGoalExactlyOneAnimalOfTypePerColonne($playerId, ANIMAL_PINGUIN);
            case 18:
                return $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_GORILLA, false, 5, 2) + $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_PANDA, false, 5, 2);
            case 21:
                return $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_CROCODILE, false, 5, 2) + $this->calculateGoalRelativeAnimalsCount($playerId, ANIMAL_FLAMINGO, true, 5, 2);
            case 13:
                $giraffeCountPoints = [8, 5, 3, 0];
                return $giraffeCountPoints[min(3, $this->countAnimals($grid, ANIMAL_GIRAFFE))];

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
            $biomes = $this->getRotatedBiomes($card);
            $anchorCol = $card->location_arg % GRID_SIZE - 1; //array starting at 0
            $anchorRow = ceil($card->location_arg / GRID_SIZE) - 1; //same
            //anchor is top left part of the card
            $grid[$anchorRow][$anchorCol] = $biomes[0];
            $grid[$anchorRow][$anchorCol + 1] = $biomes[1];
            $grid[$anchorRow + 1][$anchorCol] = $biomes[2];
            $grid[$anchorRow + 1][$anchorCol + 1] = $biomes[3];
        }
        $this->displayGrid($grid);
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
        if (count($players) == 3 || count($players) == 4) {
            $animalCountCopy = $animalCount;
            sort($animalCountCopy);
            if (count($animalCountCopy) > 1) {
                $second =  $animalCount[$currentPlayerId] == $animalCountCopy[1];
            }
            $tieForFirst = count(array_filter($animalCount, fn ($nb) => $nb == $min ? min($animalCount) : max($animalCount)));
            if (!$tieForFirst && $second) {
                return $secondPoints;
            }
        }
        return ($min ? min($animalCount) : max($animalCount)) == $animalCount[$currentPlayerId] ? $winnerPoints : 0;
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

    function exploreLandZone($grid, $visited, $i, $j, $rows, $cols, &$count, int $land) {
        if ($i < 0 || $i >= $rows || $j < 0 || $j >= $cols || $visited[$i][$j] || $grid[$i][$j]->land != $land)
            return;

        $visited[$i][$j] = true;
        $count++;

        // Explorer les cases voisines (à droite, en bas, en haut, à gauche)
        $this->exploreLandZone($grid, $visited, $i, $j + 1, $rows, $cols, $count, $land);
        $this->exploreLandZone($grid, $visited, $i + 1, $j, $rows, $cols, $count, $land);
        $this->exploreLandZone($grid, $visited, $i - 1, $j, $rows, $cols, $count, $land);
        $this->exploreLandZone($grid, $visited, $i, $j - 1, $rows, $cols, $count, $land);
    }

    function calculateLandZones($grid, int $land) {
        $count = 0;
        $rows = count($grid);
        $cols = count($grid[0]);
        $visited = array_fill(0, $rows, array_fill(0, $cols, false));

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($grid[$i][$j]->land == $land && !$visited[$i][$j]) {
                    // Explorer la zone de savane à partir de cette cellule
                    $zoneCount = 0;
                    $this->exploreLandZone($grid, $visited, $i, $j, $rows, $cols, $zoneCount, $land);
                    if ($zoneCount === 4) {
                        $count++;
                    }
                }
            }
        }

        return $count * 6;
    }

    function calculateGoalCompleteSquare($grid) {
        $rows = count($grid);
        $cols = count($grid[0]);

        // Initialiser une matrice pour stocker la taille des carrés complets à chaque position
        $squareSizes = array_fill(0, $rows, array_fill(0, $cols, 0));

        // Remplir la première colonne de la matrice avec les valeurs de la première colonne de la grille
        for ($i = 0; $i < $rows; $i++) {
            $squareSizes[$i][0] = ($grid[$i][0]->land != FAKE_LAND) ? 1 : 0;
        }

        // Remplir la première ligne de la matrice avec les valeurs de la première ligne de la grille
        for ($j = 0; $j < $cols; $j++) {
            $squareSizes[0][$j] = ($grid[0][$j]->land != FAKE_LAND) ? 1 : 0;
        }

        // Calculer les tailles des carrés complets pour les positions restantes
        for ($i = 1; $i < $rows; $i++) {
            for ($j = 1; $j < $cols; $j++) {
                if ($grid[$i][$j]->land != FAKE_LAND) {
                    // Trouver la taille minimale des carrés complets adjacents
                    $minSize = min(
                        $squareSizes[$i - 1][$j],      // En haut
                        $squareSizes[$i][$j - 1],      // À gauche
                        $squareSizes[$i - 1][$j - 1]   // En diagonale (haut gauche)
                    );

                    // La taille du carré complet actuel est la taille minimale + 1
                    $squareSizes[$i][$j] = $minSize + 1;
                }
            }
        }

        // Trouver la taille maximale dans la dernière colonne (partant du bas à gauche)
        $maxSize = max(...$squareSizes[$rows - 1]);

        $points = [0, 0, 3, 5, 8, 13, 21];
        return $points[$maxSize];
    }
}
