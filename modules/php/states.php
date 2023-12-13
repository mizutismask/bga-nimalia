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
        $lastCards = $this->getLastCardPlayed();
        foreach ($playersIds as $playerId) {
            self::notifyAllPlayers('cardsMove', "", [
                "playerId" => $playerId,
                "playedCard" =>  $lastCards[$playerId]
            ]);
        }
        $this->draftCards();
        if (count($this->getPlayerCards(array_pop($playersIds))) == 0) {
            $this->gamestate->nextState("endScore");
        } else {
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
        self::notifyAllPlayers('newRound', clienttranslate('Round ${round}'), $this->getRoundArgs());

        $this->gamestate->nextState('');
    }

    function getRoundArgs() {
        $round = intval(self::getGameStateValue(ROUND));
        return ["round" => $round, "clockwise" => $this->isClockWisePlayerOrder(), "goals" => $this->getRoundGoals()];
    }

    function getScoreArgs() {
        $scores = [];
        foreach (range(1, 5) as $round) {
            foreach ($this->getPlayersIds() as $playerId) {
                $total = 0;
                foreach ($this->getRoundGoals($round) as $goal) {
                    $statName = "game_pointsRound" . $round . $goal->color;
                    $score = self::getStat($statName, $playerId);
                    $total += $score;
                    $scores[] = ["playerId" => $playerId, "score" => $score, "scoreType" => $this->getScoreType($round, $goal->color, $playerId)];
                }
                $scores[] = ["playerId" => $playerId, "score" => $total, "scoreType" =>  $this->getTotalType($round, $playerId)];
            }
        }
        return $scores;
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
                $goalColor = $goal->color;
                $this->incPlayerScore($playerId, $score, clienttranslate('${player_name} gains ${delta} points with the ${color} goal'), ["color" => $this->getColorName($goal->color), "scoreType" => $this->getScoreType($round, $goalColor, $playerId)]);
                $roundScores[$playerId] += $score;
                $totalScore[$playerId] += $score;
            }
        }

        foreach ($players as $playerId => $playerDb) {
            $this->notifyPlayerScore($playerId, $roundScores[$playerId], clienttranslate('${player_name} gains a total of ${score} points for the round ${round}'), ["round" => $round, "scoreType" => $this->getTotalType($round, $playerId)]);
        }

        //round winner
        $this->notifyWinner($players, $roundScores);

        if ($round == 5) {
            //game winner
            $this->notifyWinner($players, $totalScore);
        }

        //total from the beginning
        foreach ($players as $playerId => $playerDb) {
            $this->incPlayerScore($playerId, 0, null, ["scoreType" => "total-$playerId"]);
        }
        $this->gamestate->nextState("seeScore");
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
