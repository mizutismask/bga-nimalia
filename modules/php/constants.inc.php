<?php

/* 
 * Game constants 
 */
const LAND_JUNGLE = 1;
const LAND_WATER = 2;
const LAND_SNOW = 3;
const LAND_SAVANNAH = 4;

const ANIMAL_FLAMINGO = 1;
const ANIMAL_LION = 2;
const ANIMAL_BEAR = 3;
const ANIMAL_CROCODILE = 4;
const ANIMAL_GIRAFFE = 5;
const ANIMAL_GORILLA = 6;
const ANIMAL_OTTER = 7;
const ANIMAL_PANDA = 8;
const ANIMAL_PINGUIN = 9;

/**
 * Options
 */
define('EXPANSION', 0); // 0 => base game

/*
 * State constants
 */
define('ST_BGA_GAME_SETUP', 1);
define('ST_DEAL_INITIAL_SETUP', 10);

define('ST_PLAYER_CHOOSE_ACTION', 30);

define('ST_NEXT_PLAYER', 80);
define('ST_NEXT_REVEAL', 81);

define('ST_DEBUG_END_GAME', 97);
define('ST_END_SCORE', 98);

define('ST_END_GAME', 99);
define('END_SCORE', 100);


/*
 * Variables (numbers)
 */

define('LAST_TURN', 'LAST_TURN');


/*
 * Global variables (objects)
 */
//define('LAST_BLUE_ROUTES', 'LAST_BLUE_ROUTES'); //array of the 3 last arrows

/*
    Stats
*/
//define('STAT_POINTS_WITH_PLAYER_COMPLETED_DESTINATIONS', 'pointsWithPlayerCompletedDestinations');
