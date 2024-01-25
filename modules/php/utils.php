<?php
require_once(__DIR__ . '/objects/BiomesCard.php');
//require_once(__DIR__ . '/objects/route.php');

trait UtilTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////
    function getColorName(int $color) {
        switch ($color) {
            case GOAL_BLUE:
                return clienttranslate("blue");
            case GOAL_YELLOW:
                return clienttranslate("yellow");
            case GOAL_RED:
                return clienttranslate("red");
            case GOAL_GREEN:
                return clienttranslate("green");
        }
    }

    function isClockWisePlayerOrder() {
        $round = self::getGameStateValue(ROUND);
        return $round == 1 || $round == 3 || $round == 5;
    }

    function array_find(array $array, callable $fn) {
        foreach ($array as $value) {
            if ($fn($value)) {
                return $value;
            }
        }
        return null;
    }

    function array_find_index(array $array, callable $fn) {
        foreach ($array as $index => $value) {
            if ($fn($value)) {
                return $index;
            }
        }
        return null;
    }

    function array_some(array $array, callable $fn) {
        foreach ($array as $value) {
            if ($fn($value)) {
                return true;
            }
        }
        return false;
    }

    function array_every(array $array, callable $fn) {
        foreach ($array as $value) {
            if (!$fn($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Save (insert or update) any object/array as variable.
     */
    function setGlobalVariable(string $name, /*object|array*/ $obj) {
        $jsonObj = json_encode($obj);
        $this->DbQuery("INSERT INTO `global_variables`(`name`, `value`)  VALUES ('$name', '$jsonObj') ON DUPLICATE KEY UPDATE `value` = '$jsonObj'");
    }

    /**
     * Return a variable object/array.
     * To force object/array type, set $asArray to false/true.
     */
    function getGlobalVariable(string $name, $asArray = null) {
        $json_obj = $this->getUniqueValueFromDB("SELECT `value` FROM `global_variables` where `name` = '$name'");
        if ($json_obj) {
            $object = json_decode($json_obj, $asArray);
            return $object;
        } else {
            return null;
        }
    }

    /**
     * Delete a variable object/array.
     */
    function deleteGlobalVariable(string $name) {
        $this->DbQuery("DELETE FROM `global_variables` where `name` = '$name'");
    }

    function incGlobalVariable(string $globalVariableName, int $value) {
        $old = $this->getGameStateValue($globalVariableName);
        $this->setGameStateValue($globalVariableName, $old + $value);
    }

    /**
     * Transforms a BiomesCard Db object to BiomesCard class.
     */
    function getBiomesCardFromDb($dbObject): BiomeCard {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("BiomesCard doesn't exists " . json_encode($dbObject));
        }
        $sql = "SELECT card_order_in_grid, card_rotation FROM card where card_id=". $dbObject['id'];
        $additionalFields = self::getObjectFromDB( $sql );
        //self::dump('************type_arg*******', $dbObject["type_arg"]);
        //self::dump('*******************', $this->BIOMES_CARDS[$dbObject["type"]][$dbObject["type_arg"]]);
        //self::dump('*******************additionalFields', $additionalFields);
        return new BiomeCard($dbObject, $this->BIOMES_CARDS, $additionalFields);
    }

    /** Gets the next player after a given playerId if the round is clockwise, or the previous player if not. */
    function getRecipientPlayer(int $playerId) {
        if ($this->isClockWisePlayerOrder())
            return self::getPlayerAfter($playerId);
        return self::getPlayerBefore($playerId);
    }

    /**
     * Transforms a BiomesCard Db object array to BiomesCard class array.
     */
    function getBiomesCardsFromDb(array $dbObjects) {
        return array_map(fn ($dbObject) => $this->getBiomesCardFromDb($dbObject), array_values($dbObjects));
    }

    function getIds(array $biomeCards) {
        $ids = [];
        foreach ($biomeCards as $card) {
            $ids[] = $card->id;
        }
        return $ids;
    }

    /**
     * Transforms a ClaimedRoute json decoded object to ClaimedRoute class.
     */
    /* function getClaimedRouteFromGlobal($dbObject) {
        //self::dump('*******************getClaimedRouteFromGlobal', $dbObject);
        if (
            $dbObject === null
        ) {
            return null;
        }
        if (!$dbObject) {
            throw new BgaSystemException("Claimed route doesn't exists " . json_encode($dbObject));
        }

        $class = new ClaimedRoute([]);
        foreach ($dbObject as $key => $value) $class->{$key} = $value;
        return $class;
    }*/

    function isCardCoveringAnotherCard(int $player, BiomeCard $cardId, int $squareId) {
        return true;//todo use getPossibleSquares
    }

    function getNonZombiePlayersIds() {
        $sql = "SELECT player_id FROM player WHERE player_eliminated = 0 AND player_zombie = 0 ORDER BY player_no";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(fn ($dbResult) => intval($dbResult['player_id']), array_values($dbResults));
    }

    function getPlayersIds() {
        return array_keys($this->loadPlayersBasicInfos());
    }

    function getPlayerIdsInOrder($starting) {
        $player_ids = $this->getPlayersIds();
        $rotate_count = array_search($starting, $player_ids);
        if ($rotate_count === false) {
            return $player_ids;
        }
        for ($i = 0; $i < $rotate_count; $i++) {
            array_push($player_ids, array_shift($player_ids));
        }
        return $player_ids;
    }

    function getPlayerCount() {
        return count($this->getPlayersIds());
    }

    function getPlayerIdByOrder($playerOrder = 1) {
        return $this->getUniqueIntValueFromDB("SELECT player_id FROM player where `player_no` = $playerOrder");
    }

    function getLastPlayer() {
        return $this->getPlayerIdByOrder($this->getPlayerCount());
    }

    function getPlayerName(int $playerId) {
        return self::getUniqueValueFromDb("SELECT player_name FROM player WHERE player_id = $playerId");
    }

    function isLastPlayer(int $playerId) {
        return $this->getLastPlayer() == $playerId;
    }

    function getPlayerScore(int $playerId) {
        return $this->getUniqueIntValueFromDB("SELECT player_score FROM player where `player_id` = $playerId");
    }

    function incPlayerScore(int $playerId, int $delta, $message = null, $messageArgs = []) {
        self::DbQuery("UPDATE player SET `player_score` = `player_score` + $delta where `player_id` = $playerId");

        self::notifyAllPlayers('points', $message !== null ? $message : '', [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $this->getPlayerScore($playerId),
            'delta' => $delta,
        ] + $messageArgs);

        if($this->getPlayerScore($playerId)<0){
            self::DbQuery("UPDATE player SET `player_score` = 0 where `player_id` = $playerId");

            self::notifyAllPlayers('points',clienttranslate('Score can not be negative, reset to 0 for ${player_name}'), [
                'playerId' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'points' => $this->getPlayerScore($playerId),
                'delta' => 0,
            ] + $messageArgs);
        }
    }

    function notifyPlayerScore(int $playerId, int $score, $message = null, $messageArgs = [], $stat=null) {
        if($stat){
            self::incStat($score, $stat, $playerId);
        }
        self::notifyAllPlayers('score', $message !== null ? $message : '', [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'score' => $score,
        ] + $messageArgs);
    }

    function getScoreType($round, $goalColor, $playerId){
        return "round-${round}-goal-${goalColor}-${playerId}";
    }

    function getTotalType($round,  $playerId){
        return "total-round-${round}-${playerId}";
    }
    
    function updatePlayer(int $playerId, String $field, int $newValue) {
        $this->DbQuery("UPDATE player SET $field = $newValue WHERE player_id = $playerId");
    }

    function updatePlayersExceptOne(int $playerId, String $field, int $newValue) {
        $this->DbQuery("UPDATE player SET $field = $newValue WHERE player_id != $playerId");
    }

    function getPlayerFieldValue(int $playerId, String $field) {
        return self::getUniqueValueFromDB("select $field from player WHERE player_id = $playerId");
    }

    /**
     * Auto initialize stats. Note for this to work your game stats ids have to be prefixed by game_ (verbatim)
     */
    public function initStats() {
        $all_stats = $this->getStatTypes();
        $player_stats = $all_stats['player'];
        // auto-initialize all stats that starts with game_
        // we need a prefix because there is some other system stuff
        foreach ($player_stats as $key => $value) {
            if ($this->startsWith($key, 'game_')) {
                $this->initStat('player', $key, 0);
            }
            if ($key === 'turns_number') {
                $this->initStat('player', $key, 0);
            }
        }
        $table_stats = $all_stats['table'];
        foreach ($table_stats as $key => $value) {
            if ($this->startsWith($key, 'game_')) {
                $this->initStat('table', $key, 0);
            }
            if ($key === 'turns_number') {
                $this->initStat('table', $key, 0);
            }
        }
    }

    function startsWith(string $haystack, string $needle): bool {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    function endsWith(string $haystack, string  $needle): bool {
        $length = strlen($needle);
        return $length === 0 || (substr($haystack, -$length) === $needle);
    }

    function getPart(string $haystack, int $i, bool $noException = false, string $separator='_'): string {
        $parts = explode($separator, $haystack);
        $len = count($parts);
        if ($noException && $i >= $len)
            return "";
        if ($noException && $len + $i < 0)
            return "";

        return $parts[$i >= 0 ? $i : $len + $i];
    }

    function getPartsPrefix(string $haystack, int $i) {
        $parts = explode('_', $haystack);
        $len = count($parts);
        if ($i < 0) {
            $i = $len + $i;
        }
        if ($i <= 0)
            return '';
        for (; $i < $len; $i++) {
            unset($parts[$i]);
        }
        return implode('_', $parts);
    }

    function toJson($data, $options = JSON_PRETTY_PRINT) {
        $json_string = json_encode($data, $options);
        return $json_string;
    }


    function getUniqueIntValueFromDB(string $sql) {
        return intval(self::getUniqueValueFromDB($sql));
    }

    function getUniqueBoolValueFromDB(string $sql) {
        return boolval(self::getUniqueValueFromDB($sql));
    }

    function dbIncField(String $table, String $field, int $value, String $pkfield, String $key) {
        $this->DbQuery("UPDATE $table SET $field = $field+$value WHERE $pkfield = '$key'");
    }

    function getColoredGameStateValue($gameStateValue, $color) {
        return $this->getGameStateValue($gameStateValue . "_" . strtoupper($this->getColorName($color)));
    }
}
