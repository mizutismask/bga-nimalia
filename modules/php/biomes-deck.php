<?php

require_once(__DIR__ . '/objects/BiomesCard.php');

trait BiomesCardTrait {

    /**
     * Create cards.
     */
    public function createBiomes() {
        $biomesCards = $this->getBiomesToGenerate();

        $this->biomesCards->createCards($biomesCards, 'deck');
        $this->biomesCards->shuffle('deck');
    }

    /**
     * Pick destination cards for beginning choice.
     */
    public function pickInitialCards(int $playerId) {
        $cardsNumber = $this->getInitialBiomesCardNumber();
        $cards = $this->pickCards($playerId, $cardsNumber);
        $this->keepCards($playerId, $this->getIds($cards), $this->getInitialBiomesCardNumber());
        return $cards;
    }

    /* public function checkVisibleSharedCardsAreEnough() {
        $visibleCardsCount = intval($this->biomesCards->countCardInLocation('shared'));
        if ($visibleCardsCount < NUMBER_OF_SHARED_DESTINATION_CARDS) {
            $spots = [];
            $citiesNames = [];
            for ($i = $visibleCardsCount; $i < NUMBER_OF_SHARED_DESTINATION_CARDS; $i++) {
                $newCard = $this->getBiomesCardFromDb($this->biomesCards->pickCardForLocation('deck', 'shared', $i));
                $citiesNames[] = $this->CITIES[$newCard->to];
                $spots[] = $newCard;
            }
            $this->notifyAllPlayers('newSharedDestinationsOnTable', clienttranslate('New shared destination drawn: ${cities_names}'), [
                'sharedDestinations' => $spots,
                'cities_names' => implode(",", $citiesNames),
            ]);
        }
    }*/

    /**
     * Get cards in player hand.
     */
    public function getPlayerCards(int $playerId) {
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("hand", $playerId));
        return $cards;
    }

    /**
     * Get cards in player grid.
     */
    public function getGridCards(int $playerId) {
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("grid$playerId"));
        return $cards;
    }

    public function getCard(int $cardId): BiomeCard {
        return $this->getBiomesCardFromDb($this->biomesCards->getCard($cardId));
    }

    public function getLastCardPlayed(): array {
        $cards = [];
        foreach ($this->getPlayersIds() as $playerId) {
            $cards[$playerId] = $this->getBiomesCardFromDb($this->biomesCards->getCard($this->getPlayerFieldValue($playerId, PLAYER_FIELD_LAST_PLACED_CARD)));
        }
        return $cards;
    }

    /**
     * get remaining cards in deck.
     */
    public function getRemainingCardsInDeck() {
        $remaining = intval($this->biomesCards->countCardInLocation('deck'));

        if ($remaining == 0) {
            $remaining = intval($this->biomesCards->countCardInLocation('discard'));
        }

        return $remaining;
    }

    /**
     * place a number of biomesCards cards to pick$playerId.
     */
    private function pickCards($playerId, int $number) {
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->pickCardsForLocation($number, 'deck', "pick$playerId"));
        return $cards;
    }

    /**
     * Move selected cards to player hand.
     */
    private function keepCards(int $playerId, array $ids) {
        $this->biomesCards->moveCards($ids, 'hand', $playerId);
        self::notifyPlayer($playerId, 'cardsMove', "", ["playerId" => $playerId, "added" => $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation('hand', $playerId))]);
    }

    public function moveCardToReserve(int $playerId, int $cardId, int $squareId, int $rotation) {
        //todo keep order
        $this->biomesCards->moveCard($cardId, "grid$playerId", $squareId);
        $order =  self::getUniqueValueFromDB("select max(card_order_in_grid)+1 from card where card_location= 'grid$playerId'");
        $sql = "UPDATE card set card_rotation=$rotation, card_order_in_grid=$order where card_id='$cardId'";
        self::DbQuery($sql);
        $this->updatePlayer($playerId, PLAYER_FIELD_LAST_PLACED_CARD, $cardId);
        $recipient = $this->getRecipientPlayer($playerId);
        $this->biomesCards->moveAllCardsInLocation('hand', 'nextchoice', $playerId, $recipient);
        self::notifyAllPlayers('msg', clienttranslate('${player_name} places a card in square ${squareId}'), ['player_name' => $this->getPlayerName($playerId), 'squareId' => $squareId]);
    }

    public function undoMoveCardToReserve(int $playerId, int $cardId) {
        $this->biomesCards->moveCard($cardId, 'hand', $playerId);
        $this->updatePlayer($playerId, PLAYER_FIELD_LAST_PLACED_CARD, 0);
        $recipient = $this->getRecipientPlayer($playerId);
        $this->biomesCards->moveAllCardsInLocation('nextchoice', 'hand', $recipient, $playerId);
        $sql = "UPDATE card set card_order_in_grid = 0 where card_location='hand' and card_location_arg = '$playerId'";
        self::DbQuery($sql);
        self::notifyPlayer($playerId, 'cardsMove',  clienttranslate('${player_name} changes his mind'), ["playerId" => $playerId, 'player_name' => $this->getPlayerName($playerId), "added" => $this->getPlayerCards($playerId), "fromUndo" => true, "undoneCard" => $this->getCard($cardId)]);
    }

    public function draftCards() {
        $this->biomesCards->moveAllCardsInLocationKeepOrder('nextchoice', 'hand');
        $players = $this->loadPlayersBasicInfos();
        //Remaining cards are drafted
        foreach ($players as $playerId => $player) {
            self::notifyPlayer($playerId, 'cardsMove', "", ["playerId" => $playerId, "added" => $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation('hand', $playerId))]);;
        }
    }

    public function shiftCards($playerId, string $direction) {
        $cards = $this->getGridCards(($playerId));

        foreach ($cards as $card) {
            $cardId = $card->id;
            switch ($direction) {
                case 'up':
                    $this->biomesCards->moveCard($cardId, "grid$playerId", $card->location_arg - GRID_SIZE);
                    break;
                case 'down':
                    $this->biomesCards->moveCard($cardId, "grid$playerId", $card->location_arg + GRID_SIZE);
                    break;
                case 'left':
                    $this->biomesCards->moveCard($cardId, "grid$playerId", $card->location_arg - 1);
                    break;
                case 'right':
                    $this->biomesCards->moveCard($cardId, "grid$playerId", $card->location_arg + 1);
                    break;
                default:
                    //not possible
            }
        }

        $canShiftAgain = [];
        $grid = $this->getGrid($playerId);
        $canShiftAgain["up"] = $this->canShiftGrid($grid, "up");
        $canShiftAgain["down"] = $this->canShiftGrid($grid, "down");
        $canShiftAgain["left"] = $this->canShiftGrid($grid, "left");
        $canShiftAgain["right"] = $this->canShiftGrid($grid, "right");

        self::notifyAllPlayers('gridMoved', "", [
            "playerId" => $playerId,
            "cards" => $this->getPlayerGridCards($playerId),
            "canShiftGrid" => $canShiftAgain,
            "possibleSquares" => $this->getPossibleSquares()[$playerId],
        ]);
    }

    public function getPlayerGridCards($playerId) {
        $stateName = $this->gamestate->state()['name'];
        $currentPlayerId = self::getCurrentPlayerId();
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("grid$playerId", null, "card_order_in_grid"));
        if ($stateName === "placeCard" && $currentPlayerId != $playerId) {
            //do not show unrevealed last card
            $card = array_filter($cards, fn ($card) => $card->id != $this->getPlayerFieldValue($playerId, PLAYER_FIELD_LAST_PLACED_CARD));
        }
        return $cards;
    }
}
