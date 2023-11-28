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
        foreach ($playersIds as $playerId) {
            $squares[$playerId] = [];
            //$grid = $this->getGrid();
            for ($i = 1; $i <= 36; $i++) {
                $squares[$playerId][] = "square-" . $playerId . "-" . $i;
            }
        }
        return $squares;
    }
}
