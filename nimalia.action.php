<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nimalia implementation : © Séverine Kamycki <mizutismask@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * nimalia.action.php
 *
 * Nimalia main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/nimalia/nimalia/myAction.html", ...)
 *
 */


class action_nimalia extends APP_GameAction {
    // Constructor: please do not modify
    public function __default() {
        if (self::isArg('notifwindow')) {
            $this->view = "common_notifwindow";
            $this->viewArgs['table'] = self::getArg("table", AT_posint, true);
        } else {
            $this->view = "nimalia_nimalia";
            self::trace("Complete reinitialization of board game");
        }
    }

    // TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

    public function placeCard() {
        self::setAjaxMode();

        $cardId = self::getArg("cardId", AT_posint, true);
        $squareId = self::getArg("squareId", AT_posint, true);
        $rotation = self::getArg("rotation", AT_posint, true);

        $this->game->placeCard($cardId, $squareId, $rotation);
        self::ajaxResponse();
    }

    public function undoPlaceCard() {
        self::setAjaxMode();
        $this->game->undoPlaceCard();
        self::ajaxResponse();
    }

    public function seeScore() {
        self::setAjaxMode();
        $this->game->scoreSeen();
        self::ajaxResponse();
    }

    public function shiftGrid() {
        self::setAjaxMode();
        $direction = self::getArg("direction", AT_enum, true, null,["up", "down","left", "right"]);
        $this->game->shiftGrid($direction);
        self::ajaxResponse();
    }
}
