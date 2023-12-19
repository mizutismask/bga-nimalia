<?php

trait ActionTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    //////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in yourgamename.action.php)
    */
    /*public function chooseAdditionalDestinations(int $keptDestinationsId, int $discardedDestinationId) {
        self::checkAction('chooseAdditionalDestinations');

        $playerId = intval(self::getActivePlayerId());

        $this->keepAdditionalDestinationCards($playerId, $keptDestinationsId, $discardedDestinationId);

        if ($keptDestinationsId)
            self::incStat(1, STAT_KEPT_ADDITIONAL_DESTINATION_CARDS, $playerId);

        $this->gamestate->nextState('continue');
    }*/

    public function placeCard(int $cardId, int $squareId, int $rotation) {
        self::checkAction('placeCard');
        $playerId = intval(self::getCurrentPlayerId());
        $card = $this->getCard($cardId);
        if ($card->location != 'hand' || $card->location_arg != $playerId)
            throw new BgaUserException("You can’t place this card: " . $card->type);
        if ($rotation % 90 != 0)
            throw new BgaVisibleSystemException("Rotation is not a correct value: " . $rotation);
        if ($squareId % GRID_SIZE > 5 || $squareId % GRID_SIZE < 1)
            throw new BgaUserException("You can’t place a card outside of the 6x6 grid: " . $squareId);
        if (count($this->getGridCards($playerId)) != 0 && !$this->isCardCoveringAnotherCard($playerId, $card, $squareId))
            throw new BgaUserException("You have to to cover a part of your existing animal reserve");

        $this->moveCardToReserve($playerId,  $cardId,  $squareId,  $rotation);

        $this->gamestate->setPlayerNonMultiactive($playerId, 'cardPlaced');
    }


    public function undoPlaceCard() {
        $this->gamestate->checkPossibleAction('undoPlaceCard');
        $activePlayers = $this->getNonZombiePlayersIds();
        if(!in_array(self::getCurrentPlayerId(), $activePlayers)){
            throw new BgaUserException("You left the game and thus can not play");
        }

        $playerId = intval(self::getCurrentPlayerId());
        $cardId = $this->getPlayerFieldValue($playerId, PLAYER_FIELD_LAST_PLACED_CARD);
        if (!$cardId) {
            throw new BgaVisibleSystemException(self::_("Your last move was not saved, undo is not available"));
        }
        $this->undoMoveCardToReserve($playerId,  $cardId);
        $this->gamestate->setPlayersMultiactive([$playerId], "ignored");
    }

    public function scoreSeen() {
        self::checkAction('seen');
        $playerId = intval(self::getCurrentPlayerId());
        $nextState = $this->getScoreSeenNextState($playerId);
        $this->gamestate->setPlayerNonMultiactive($playerId, $nextState);
    }

    public function getScoreSeenNextState($playerId){
        $nextState = 'nextRound';

        if ($this->hasReachedEndOfGameRequirements(null)) {
            if ($this->getBgaEnvironment() == 'studio') {
                $this->gamestate->nextState('debugEndGame');
            } else {
                $this->gamestate->nextState('endGame');
            }
        }
        return $nextState;
    }
}
