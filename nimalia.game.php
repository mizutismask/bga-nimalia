<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nimalia implementation : © Séverine Kamycki <mizutismask@gmail.com>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * nimalia.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */


require_once(APP_GAMEMODULE_PATH . 'module/table/table.game.php');
require_once('modules/php/constants.inc.php');
require_once('modules/php/utils.php');
require_once('modules/php/states.php');
require_once('modules/php/args.php');
require_once('modules/php/actions.php');
require_once('modules/php/biomes-deck.php');
require_once('modules/php/goals.php');
require_once('modules/php/debug-util.php');
require_once('modules/php/expansion.php');

class Nimalia extends Table {
    use UtilTrait;
    use ActionTrait;
    use StateTrait;
    use ArgsTrait;
    use BiomesCardTrait;
    use DebugUtilTrait;
    use ExpansionTrait;
    use GoalTrait;

    function __construct() {
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();

        self::initGameStateLabels(array(
            LAST_TURN => 10, // last turn is the id of the last player, 0 if it's not last turn
            ROUND => 11, // round number, from 1 to 5
            PLAYER_FIELD_LAST_PLACED_CARD => 12, // last card played, to enable undo
            //    "my_second_global_variable" => 11,
            //      ...
            GOAL_LEVEL => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ));
        $this->biomesCards = $this->getNew("module.common.deck");
        $this->biomesCards->init("card");
        $this->biomesCards->autoreshuffle = true;
    }

    protected function getGameName() {
        // Used for translations and stuff. Please do not modify.
        return "nimalia";
    }

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame($players, $options = array()) {
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach ($players as $player_id => $player) {
            $color = array_shift($default_colors);
            $values[] = "('" . $player_id . "','$color','" . $player['player_canal'] . "','" . addslashes($player['player_name']) . "','" . addslashes($player['player_avatar']) . "')";
        }
        $sql .= implode(',', $values);
        self::DbQuery($sql);
        self::reattributeColorsBasedOnPreferences($players, $gameinfos['player_colors']);
        self::reloadPlayersBasicInfos();

        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( ROUND, 1 );
        //initialize everything to be compliant with undo framework
        //foreach ($this->GAMESTATELABELS as $value_label => $ID) if ($ID >= 10 && $ID < 90) $this->setGameStateInitialValue($value_label, 0);

        $this->initStats();

        // TODO: setup the initial game situation here
        $this->selectGoals();
        $this->createBiomes();

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas() {
        $stateName = $this->gamestate->state()['name'];
        $isEnd = $stateName === 'endScore' || $stateName === 'gameEnd' || $stateName === 'debugGameEnd' || $stateName === 'seeScore' && self::getGameStateValue(ROUND) == 5;

        $result = [];

        $currentPlayerId = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!

        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score, player_score_aux scoreAux, player_no playerNo FROM player ";
        $result['players'] = self::getCollectionFromDb($sql);
        $result['playerOrderWorkingWithSpectators'] = $this->getPlayerIdsInOrder($currentPlayerId);

        // TODO: Gather all information about current game situation (visible by player $current_player_id).
        $result['expansion'] = EXPANSION;
        if ($isEnd) {
            if ($stateName === "seeScore") {
                $maxScore = max(array_map(fn ($player) => intval($player['score']), $result['players']));
                $result['winners'] = array_keys(array_filter($result['players'], fn ($player) => intval($player['score'] == $maxScore)));
                if (count($result['winners']) > 1) {
                    $tieWinners =  array_filter($result['players'], fn ($player) => in_array($player["id"], $result['winners']));
                    $maxScore = max(array_map(fn ($player) => intval($player['scoreAux']),$tieWinners));
                    $result['winners'] = array_keys(array_filter($tieWinners, fn ($player) => intval($player['scoreAux'] == $maxScore )));
                }
            }
        } else {
            $result['lastTurn'] = $this->getGameStateValue(LAST_TURN) > 0;
        }

        // shared information
        $result['turnOrderClockwise'] = true;
        $result['round'] = $this->getRoundArgs();
        $result["goals"] = $this->getGameGoals();
        $result["scores"] = $this->getScoreArgs();
        foreach ($result['players'] as $playerId => &$player) {
            $player['playerNo'] = intval($player['playerNo']);
            $result['grids'][$playerId] = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("grid$playerId", null, "card_order_in_grid"));
            if ($stateName === "placeCard" && $currentPlayerId != $playerId) {
                //do not show unrevealed last card
                $result['grids'][$playerId] = array_filter($result['grids'][$playerId], fn ($card) => $card->id != $this->getPlayerFieldValue($playerId, PLAYER_FIELD_LAST_PLACED_CARD));
            }
        }

        // private data : current player hidden informations
        $result['hand'] = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation('hand', $currentPlayerId));

        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression() {
        $stateName = $this->gamestate->state()['name'];
        if ($stateName === 'endScore' || $stateName === 'gameEnd') {
            // game is over
            return 100;
        }
        $playerIds = $this->getNonZombiePlayersIds();
        return 100 * ($this->biomesCards->countCardInLocation('grid' . $playerIds[0])) / (3 * 5);
    }


    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */

    //////////////////////////////////////////////////////////////////////////////
    //////////// Zombie
    ////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */
    function zombieTurn($state, $active_player) {
        $statename = $state['name'];
        if ($statename === "seeScore") {
            $this->gamestate->setPlayerNonMultiactive($active_player, $this->getScoreSeenNextState($active_player));
            return;
        }
        if ($statename === "placeCard") {
            //plays a random move so that cards are drafted and everyone has the same amount of cards
            $zombieHand = $this->biomesCards->getPlayerHand($active_player);
            if (count($zombieHand)) {
                $squareId = intval($this->getPart(array_pop($this->getPossibleSquares()[$active_player]), -1, false, "-"));
                $cardId = intval(array_pop($zombieHand)["id"]);
                $this->moveCardToReserve($active_player,  $cardId,  $squareId,  0);
            }
            $this->gamestate->setPlayerNonMultiactive($active_player, 'cardPlaced');
            return;
        }

        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState("zombiePass");
                    break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive($active_player, '');

            return;
        }

        throw new feException("Zombie mode not supported at this game state: " . $statename);
    }

    ///////////////////////////////////////////////////////////////////////////////////:
    ////////// DB upgrade
    //////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */

    function upgradeTableDb($from_version) {
        $changes = [
            // [2307071828, "INSERT INTO DBPREFIX_global (`global_id`, `global_value`) VALUES (24, 0)"], 
        ];

        foreach ($changes as [$version, $sql]) {
            if ($from_version <= $version) {
                try {
                    self::warn("upgradeTableDb apply 1: from_version=$from_version, change=[ $version, $sql ]");
                    self::applyDbUpgradeToAllDB($sql);
                } catch (Exception $e) {
                    // See https://studio.boardgamearena.com/bug?id=64
                    // BGA framework can produce invalid SQL with non-existant tables when using DBPREFIX_.
                    // The workaround is to retry the query on the base table only.
                    self::error("upgradeTableDb apply 1 failed: from_version=$from_version, change=[ $version, $sql ]");
                    $sql = str_replace("DBPREFIX_", "", $sql);
                    self::warn("upgradeTableDb apply 2: from_version=$from_version, change=[ $version, $sql ]");
                    self::applyDbUpgradeToAllDB($sql);
                }
            }
        }
        self::warn("upgradeTableDb complete: from_version=$from_version");
    }
}
