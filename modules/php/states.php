<?php

trait StateTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state actions
    ////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    function stDealInitialSetup() {
        $this->gamestate->nextState('');
    }

    function hasReachedEndOfGameRequirements($playerId): bool {
        return self::getGameStateValue(ROUND) >= 5;
    }

    function stMoveReveal() {
    }

    function stNextRound() {
        $playerId = self::getActivePlayerId();

        self::incGameStateValue(ROUND, 1);

        $playersIds = $this->getPlayersIds();
        foreach ($playersIds as $playerId) {
            $this->pickInitialCards($playerId);
        }


        $this->gamestate->nextState('');
    }


    function stScore() {
        $sql = "SELECT player_id id, player_score score, player_no playerNo FROM player ORDER BY player_no ASC";
        $players = self::getCollectionFromDb($sql);

        // points gained during the game
        $totalScore = [];
        foreach ($players as $playerId => $playerDb) {
            $totalScore[$playerId] = intval($playerDb['score']);
        }

        //end of game points

        // failed biomesCards 
        /* $destinationsResults = [];
        $completedDestinationsCount = [];
        foreach ($players as $playerId => $playerDb) {
            $completedDestinationsCount[$playerId] = 0;
            $uncompletedDestinations = [];
            $completedDestinations = [];

            $biomesCards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation('hand', $playerId));

            foreach ($biomesCards as &$destination) {
                $completed = boolval(self::getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                if ($completed) {
                    $completedDestinationsCount[$playerId]++;
                    $completedDestinations[] = $destination;
                    self::incStat(1, STAT_POINTS_WITH_PLAYER_COMPLETED_DESTINATIONS, $playerId);
                } else {
                    $totalScore[$playerId] += -1;
                    self::incScore($playerId, -1);
                    if ($this->isDestinationRevealed($destination->id)) {
                        $totalScore[$playerId] += -1;
                        self::incScore($playerId, -1);
                        self::incStat(-1, STAT_POINTS_WITH_REVEALED_DESTINATIONS, $playerId);
                    }
                    self::incStat(1, STAT_POINTS_LOST_WITH_UNCOMPLETED_DESTINATIONS, $playerId);
                    $uncompletedDestinations[] = $destination;
                }
            }

            $destinationsResults[$playerId] = $uncompletedDestinations;
        }
*/
        foreach ($players as $playerId => $playerDb) {
            self::DbQuery("UPDATE player SET `player_score` = $totalScore[$playerId] where `player_id` = $playerId");
            self::DbQuery("UPDATE player SET `player_score_aux` = `player_remaining_tickets` where `player_id` = $playerId");
        }

        $bestScore = max($totalScore);
        $playersWithScore = [];
        foreach ($players as $playerId => &$player) {
            $player['playerNo'] = intval($player['playerNo']);
            $player['ticketsCount'] = $this->getRemainingTicketsCount($playerId);
            $player['score'] = $totalScore[$playerId];
            $playersWithScore[$playerId] = $player;
        }
        self::notifyAllPlayers('bestScore', '', [
            'bestScore' => $bestScore,
            'players' => array_values($playersWithScore),
        ]);

        // highlight winner(s)
        foreach ($totalScore as $playerId => $playerScore) {
            if ($playerScore == $bestScore) {
                self::notifyAllPlayers('highlightWinnerScore', '', [
                    'playerId' => $playerId,
                ]);
            }
        }

        if($this->hasReachedEndOfGameRequirements(null)){
            if ($this->getBgaEnvironment() == 'studio') {
                $this->gamestate->nextState('debugEndGame');
            } else {
                $this->gamestate->nextState('endGame');
            }
        }else{
            $this->gamestate->nextState('nextRound');
        }
    }
}
