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

    /** Notifies everyone’s move at the same time when everyone has finished his move. Also gets drafted cards for the next move. */
    function stMoveReveal() {
        $playersIds = $this->getPlayersIds();
        if (count($this->getPlayerCards(array_pop($playersIds))) == 0) {
            $this->gamestate->nextState("endScore");
        } else {
            $this->getDraftedCards();
            $this->gamestate->nextState("nextCard");
        }
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
        $round = self::getGameStateValue(ROUND);

        // points gained during previous rounds
        $totalScore = [];
        foreach ($players as $playerId => $playerDb) {
            $totalScore[$playerId] = intval($playerDb['score']);
        }

        //this round points
        $roundScores = array_fill_keys(array_keys($players), 0);
        foreach ($this->getRoundGoals() as $goal) {
            foreach ($players as $playerId => $playerDb) {
                $score = $this->calculateGoalPoints($goal, $playerId);
                self::incStat($score, "game_pointsRound" . $round . $goal->color, $playerId);
                self::incScore($playerId, $score);
                $roundScores[$playerId] += $score;
                $totalScore[$playerId] += $score;
            }
        }

        //round winner
        $this->notifyWinner($players, $roundScores);

        if ($round == 5) {
            //game winner
            $this->notifyWinner($players, $totalScore);
        }

        if ($this->hasReachedEndOfGameRequirements(null)) {
            if ($this->getBgaEnvironment() == 'studio') {
                $this->gamestate->nextState('debugEndGame');
            } else {
                $this->gamestate->nextState('endGame');
            }
        } else {
            $this->gamestate->nextState('nextRound');
        }
    }

    function notifyWinner($players, $roundScores) {
        $bestScore = max($roundScores);
        $playersWithScore = [];
        foreach ($players as $playerId => &$player) {
            $player['playerNo'] = intval($player['playerNo']);
            $player['score'] = $roundScores[$playerId];
            $playersWithScore[$playerId] = $player;
        }
        self::notifyAllPlayers('bestScore', '', [
            'bestScore' => $bestScore,
            'players' => array_values($playersWithScore),
        ]);

        // highlight winner(s)
        foreach ($roundScores as $playerId => $playerScore) {
            if ($playerScore == $bestScore) {
                self::notifyAllPlayers('highlightWinnerScore', '', [
                    'playerId' => $playerId,
                ]);
            }
        }
    }
}
