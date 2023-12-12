<?php

trait ArgsTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state arguments
    ////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */
    /*function argChooseAdditionalDestinations() {
        $playerId = intval(self::getActivePlayerId());

        $biomesCards = $this->getPickedDestinationCards($playerId);

        return [
            'minimum' => 3,
            '_private' => [          // Using "_private" keyword, all data inside this array will be made private
                'active' => [       // Using "active" keyword inside "_private", you select active player(s)
                    'biomesCards' => $biomesCards,   // will be send only to active player(s)
                ]
            ],
        ];
    }
*/

    function argPlaceCard() {
        return [
            'possibleSquares' => $this->getPossibleSquares(),
        ];
    }

    function getPossibleSquares() {
        $squares = [];
        $playersIds = $this->getPlayersIds();
        $terrainType = FAKE_LAND;
        foreach ($playersIds as $playerId) {
            $squares[$playerId] = [];
            $grid = $this->getGrid($playerId);

            // every row except the last one
            for ($row = 0; $row < GRID_SIZE - 1; $row++) {
                //  every column except the last one
                for ($col = 0; $col < GRID_SIZE - 1; $col++) {
                    // check current squares and right, bottom, right diagonal
                    if (
                        $grid[$row][$col]->land != $terrainType ||
                        $grid[$row][$col + 1]->land != $terrainType ||
                        $grid[$row + 1][$col]->land != $terrainType ||
                        $grid[$row + 1][$col + 1]->land != $terrainType
                    ) {
                        $squares[$playerId][] = "square-" . $playerId . "-" . ($row * GRID_SIZE + $col + 1);
                    }
                }
            }

            //beginning of the game, everything in the grid is possible
            if (count($squares[$playerId]) == 0) {
                for ($row = 0; $row < GRID_SIZE - 1; $row++) {
                    for ($col = 0; $col < GRID_SIZE - 1; $col++) {
                        $squares[$playerId][] = "square-" . $playerId . "-" . ($row * GRID_SIZE + $col + 1);
                    }
                }
            }
            //self::dump('*******************possible', $specialCases);
        }
        return $squares;
    }
}
