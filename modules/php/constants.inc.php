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

const GOAL_GREEN = 1;
const GOAL_BLUE = 2;
const GOAL_RED = 3;
const GOAL_YELLOW = 4;

const LEVEL_RANDOM = 0;
const LEVEL_EASY = 1;
const LEVEL_MEDIUM = 2;
const LEVEL_DIFFICULT = 3;

const GOAL_COLORS=[GOAL_RED, GOAL_BLUE, GOAL_GREEN, GOAL_YELLOW];
/**
 * Options
 */
define('EXPANSION', 0); // 0 => base game

/*
 * State constants
 */
define('ST_BGA_GAME_SETUP', 1);
define('ST_DEAL_INITIAL_SETUP', 10);

define('ST_NEXT_ROUND', 80);
define('ST_PLACE_CARD', 81);
define('ST_MOVE_REVEAL', 82);

define('ST_DEBUG_END_GAME', 97);
define('ST_SCORE', 98);

define('ST_END_GAME', 99);
define('END_SCORE', 100);


/*
 * Variables (numbers)
 */

define('LAST_TURN', 'LAST_TURN');
define('ROUND', 'ROUND');
define('GOAL_LEVEL', 'GOAL_LEVEL');


/*
 * Global variables (objects)
 */
//define('LAST_BLUE_ROUTES', 'LAST_BLUE_ROUTES'); //array of the 3 last arrows

/*
    Stats
*/
//define('STAT_POINTS_WITH_PLAYER_COMPLETED_DESTINATIONS', 'pointsWithPlayerCompletedDestinations');
