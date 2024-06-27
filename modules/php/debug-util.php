<?php

trait DebugUtilTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////

    function debugSetup() {
        if ($this->getBgaEnvironment() != 'studio') {
            return;
        }

        //$this->debugSetDestinationInHand(7, 2343492);
        //$this->gamestate->changeActivePlayer(2343492);
    }

    /*function cd() {
        $this->debugCompleteDestinations();
    }*/

    /*function debugCompleteDestinations() {
        $players = $this->getPlayersIds();
        $restriction = " limit " . ($this->getInitialBiomesCardNumber() - 1);
        foreach ($players as $playerId) {
            self::DbQuery("UPDATE `destination` set `completed` = true WHERE `card_location_arg`= $playerId" . $restriction);
        }
        $this->gamestate->jumpToState(ST_PLAYER_CHOOSE_ACTION);
    }*/

    /*function debugEmptyDestinationDeck() {
        $this->biomesCards->moveAllCardsInLocation('deck', 'void');
    }*/

    /*function debugAlmostEmptyDestinationDeck() {
        $moveNumber = $this->getRemainingCardsInDeck() - 1;
        $this->biomesCards->pickCardsForLocation($moveNumber, 'deck', 'discard');
    }*/


    /*function clear() {
        self::DbQuery("DELETE FROM `claimed_routes`");
        $this->setGlobalVariable(LAST_BLUE_ROUTES, [null, null, null]);
        $this->setGameStateValue(BLUEPOINT_ACTIONS_REMAINING, 0);
        $this->debugResetArrowsLeft();
        self::DbQuery("UPDATE `destination` set `completed` = false");
    }*/

    function debug($debugData) {
        if ($this->getBgaEnvironment() != 'studio') {
            return;
        }
        die('debug data : ' . json_encode($debugData));
    }

    function endGame() {
        $this->gamestate->nextState("endGame");
    }

    public function loadBugReportSQL(int $reportId, array $studioPlayers): void {
        $prodPlayers = $this->getObjectListFromDb("SELECT `player_id` FROM `player`", true);
        $prodCount = count($prodPlayers);
        $studioCount = count($studioPlayers);
        if ($prodCount != $studioCount) {
            throw new BgaVisibleSystemException("Incorrect player count (bug report has $prodCount players, studio table has $studioCount players)");
        }

        // SQL specific to your game
        // For example, reset the current state if it's already game over
        $sql = [
            "UPDATE `global` SET `global_value` = 81 WHERE `global_id` = 1 AND `global_value` = 99"
        ];
        foreach ($prodPlayers as $index => $prodId) {
            $studioId = $studioPlayers[$index];
            // SQL common to all games
            $sql[] = "UPDATE `player` SET `player_id` = $studioId WHERE `player_id` = $prodId";
            $sql[] = "UPDATE `global` SET `global_value` = $studioId WHERE `global_value` = $prodId";
            $sql[] = "UPDATE `stats` SET `stats_player_id` = $studioId WHERE `stats_player_id` = $prodId";
            $sql[] = "UPDATE gamelog SET gamelog_player=$studioId WHERE gamelog_player=$prodId";
            $sql[] = "UPDATE gamelog SET gamelog_current_player=$studioId WHERE gamelog_current_player=$prodId";
            $sql[] = "UPDATE gamelog SET gamelog_notification=REPLACE(gamelog_notification, $prodId, $studioId)";

            // SQL specific to your game
            $sql[] = "UPDATE `global_variables` SET `value` = REPLACE(`value`, $prodId, $studioId)";
            $sql[] = "UPDATE `card` SET `card_location`= REPLACE(`card_location`, $prodId, $studioId)";
            $sql[] = "UPDATE `card` SET `card_location_arg`= REPLACE(`card_location_arg`, $prodId, $studioId)";
        }
        foreach ($sql as $q) {
            $this->DbQuery($q);
        }
    }
}
