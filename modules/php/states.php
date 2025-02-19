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
            self::giveExtraTime($playerId);
            $this->updatePlayer($playerId, PLAYER_FIELD_LAST_PLACED_CARD, 0);
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
            self::giveExtraTime($playerId);
        }
        self::notifyAllPlayers('newRound', clienttranslate('&#10148; Round ${round}'), $this->getRoundArgs());

        $this->gamestate->nextState('');
    }

    function getRoundArgs() {
        $round = intval(self::getGameStateValue(ROUND));
        if ($round == 0) $round = 1; //not yet initialized
        return ["round" => $round, "clockwise" => $this->isClockWisePlayerOrder(), "goals" => $this->getRoundGoals($round)];
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
                $statName = "game_scoreRound" . $round;
                $endOfRoundScore = self::getStat($statName, $playerId) ?? 0;
                $scores[] = ["playerId" => $playerId, "score" => $endOfRoundScore, "scoreType" => "score-round-{$round}-{$playerId}"];
            }
        }
        return $scores;
    }

    function stScore() {
        $this->score();
        $this->gamestate->nextState("seeScore");
    }

    /** Called at each end of round. */
    function score() {
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
                //self::dump('*******************calculatingGoalPoints', compact("goal", "playerId"));
                $goalScore = $this->calculateGoalPoints($goal, $playerId);
                //self::dump('*******************calculatedGoalPoints', compact("goal", "score","playerId"));
                self::setStat($goalScore, "game_pointsRound" . $round . $goal->color, $playerId);
                $goalColor = $goal->color;
                $this->incPlayerScore($playerId, $goalScore, clienttranslate('${player_name} scores ${delta} points with the ${color} goal'), ["color" => $this->getColorName($goal->color), "scoreType" => $this->getScoreType($round, $goalColor, $playerId), "i18n" => ["color"]]);
                $roundScores[$playerId] += $goalScore;
                $totalScore[$playerId] += $goalScore;
            }
        }

        foreach ($players as $playerId => $playerDb) {
            $endOfRoundScore = $this->getPlayerScore($playerId);
            self::setStat($endOfRoundScore, "game_scoreRound" . $round, $playerId);
            $this->notifyPlayerScore($playerId, $roundScores[$playerId], clienttranslate('${player_name} scores a total of ${score} points for the round ${round}'), ["round" => $round, "scoreType" => $this->getTotalType($round, $playerId)]);
            $this->notifyPlayerPoints($playerId, $endOfRoundScore, "", ["round" => $round, "scoreType" => "score-round-{$round}-{$playerId}"]);
        }

        if ($round == 5) {
            //game winner
            $this->notifyWinner($totalScore, true);
        }
    }

    function notifyWinner($roundScores, bool $tie = false) {
        $bestScore = max($roundScores);
        $winners = array_keys(array_filter($roundScores, fn($p) => $p == $bestScore));
        if ($tie) {
            $winners = $this->computeTie($winners);
        }

        // highlight winner(s)
        foreach ($winners as $playerId) {
            self::notifyAllPlayers('highlightWinnerScore', '', [
                'playerId' => $playerId,
            ]);
        }
    }

    function computeTie($tiedPlayers) {
        $winners = [];
        $maxAnimals = [];
        foreach ($tiedPlayers as $playerId) {
            $grid = $this->getGrid($playerId);
            foreach (ANIMALS as $anml) {
                if (!isset($maxAnimals[$playerId])) {
                    $maxAnimals[$playerId] = 0;
                }
                $count = $this->countAnimals($grid, $anml);
                if ($count > $maxAnimals[$playerId]) {
                    $maxAnimals[$playerId] = $count;
                }
            }
        }
        $maxNumber = max($maxAnimals);
        foreach ($maxAnimals as $playerId => $anmlCount) {
            self::DbQuery("UPDATE player SET `player_score_aux` = $anmlCount where `player_id` = $playerId");
            if ($anmlCount == $maxNumber) {
                $winners[] = $playerId;
            }
        }
        return $winners;
    }
}
