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
     * Pick destination cards for pick destination action.
     */
    public function pickAdditionalDestinationCards(int $playerId) {
        return $this->pickCards($playerId, $this->getAdditionalDestinationCardNumber());
    }

    /**
     * Select kept destination card for pick destination action. 
     * Unused destination cards are discarded.
     */
    public function keepAdditionalDestinationCards(int $playerId, int $keptDestinationsId, int $discardedDestinationId) {
        $this->keepDestinationCards($playerId, $keptDestinationsId, $discardedDestinationId);
    }

    /**
     * Get destination picked cards (cards player can choose).
     */
    public function getPickedDestinationCards(int $playerId) {
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("pick$playerId"));
        return $cards;
    }

    /**
     * Get cards in player hand.
     */
    public function getPlayerCards(int $playerId) {
        $cards = $this->getBiomesCardsFromDb($this->biomesCards->getCardsInLocation("hand", $playerId));
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
     * move selected card to player hand, discard other selected card from the hand and empty pick$playerId.
     */
    private function keepDestinationCards(int $playerId, int $keptDestinationsId, int $discardedDestinationId) {
        if ($keptDestinationsId xor $discardedDestinationId) {
            throw new BgaUserException("You must discard a destination to take another one.");
        }
        $traded = $keptDestinationsId && $discardedDestinationId;
        if ($traded) {
            if (
                $this->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` = 'pick$playerId' AND `card_id` = $keptDestinationsId") == 0
                || $this->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` = 'hand' AND `card_location_arg` = '$playerId' AND `card_id` = $discardedDestinationId") == 0
            ) {
                throw new BgaUserException("Selected cards are not available.");
            }
            $this->biomesCards->moveCard($keptDestinationsId, 'hand', $playerId);
            $this->biomesCards->moveCard($discardedDestinationId, 'discard');

            $remainingCardsInPick = intval($this->biomesCards->countCardInLocation("pick$playerId"));
            if ($remainingCardsInPick > 0) {
                // we discard remaining cards in pick
                $this->biomesCards->moveAllCardsInLocationKeepOrder("pick$playerId", 'discard');
            }
        }
        $this->notifyAllPlayers('cardsPicked', clienttranslate('${player_name} trades ${count} destination'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'count' => intval($traded),
            'number' => 0, //1-1 or 0-0
            'remainingCardsInDeck' => $this->getRemainingCardsInDeck(),
            '_private' => [
                $playerId => [
                    'biomesCards' => $this->getBiomesCardsFromDb([$this->biomesCards->getCard($keptDestinationsId)]),
                    'discardedDestination' => $this->getBiomesCardFromDb($this->biomesCards->getCard($discardedDestinationId)),
                ],
            ],
        ]);
    }

    /**
     * Move selected cards to player hand.
     */
    private function  keepCards(int $playerId, array $ids) {
        $this->biomesCards->moveCards($ids, 'hand', $playerId);
        $this->notifyAllPlayers('cardsPicked', clienttranslate('${player_name} gets ${count} card(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'count' => count($ids),
            'number' => count($ids),
            'remainingCardsInDeck' => $this->getRemainingCardsInDeck(),
            '_private' => [
                $playerId => [
                    'biomesCards' => $this->getBiomesCardsFromDb($this->biomesCards->getCards($ids)),
                ],
            ],
        ]);
    }
}
