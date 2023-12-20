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
 * stats.inc.php
 *
 * Nimalia game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.
    
    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice
    ("Control Panel" / "Manage Game" / "Your Game")
    
    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean
    
    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.
    
    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress
    
    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players
    
*/
require_once 'modules/php/constants.inc.php';

$commonStats = [];

$stats_type = [

    // Statistics global to table
    "table" => $commonStats + [],

    // Statistics existing for each player. Prefix "game_" is important for auto initialization.
    "player" => $commonStats + [

        "game_pointsRound1" . GOAL_BLUE => [
            "id" => 20,
            "name" => totranslate("Round 1: blue goal"),
            "type" => "int"
        ],
        "game_pointsRound1" . GOAL_GREEN => [
            "id" => 21,
            "name" => totranslate("Round 1: green goal"),
            "type" => "int"
        ],

        "game_pointsRound2" . GOAL_GREEN => [
            "id" => 22,
            "name" => totranslate("Round 2: green goal"),
            "type" => "int"
        ],
        "game_pointsRound2" . GOAL_YELLOW => [
            "id" => 23,
            "name" => totranslate("Round 2: yellow goal"),
            "type" => "int"
        ],

        "game_pointsRound3" . GOAL_BLUE => [
            "id" => 24,
            "name" => totranslate("Round 3: blue goal"),
            "type" => "int"
        ],
        "game_pointsRound3" . GOAL_RED => [
            "id" => 25,
            "name" => totranslate("Round 3: red goal"),
            "type" => "int"
        ],

        "game_pointsRound4" . GOAL_GREEN => [
            "id" => 26,
            "name" => totranslate("Round 4: green goal"),
            "type" => "int"
        ],
        "game_pointsRound4" . GOAL_YELLOW => [
            "id" => 27,
            "name" => totranslate("Round 4: yellow goal"),
            "type" => "int"
        ],
        "game_pointsRound4" . GOAL_RED => [
            "id" => 28,
            "name" => totranslate("Round 4: red goal"),
            "type" => "int"
        ],

        "game_pointsRound5" . GOAL_BLUE => [
            "id" => 29,
            "name" => totranslate("Round 5: blue goal"),
            "type" => "int"
        ],
        "game_pointsRound5" . GOAL_RED => [
            "id" => 30,
            "name" => totranslate("Round 5: red goal"),
            "type" => "int"
        ],
        "game_pointsRound5" . GOAL_YELLOW => [
            "id" => 31,
            "name" => totranslate("Round 5: yellow goal"),
            "type" => "int"
        ],

    ],

];
