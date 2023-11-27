<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nimalia implementation : © <Your name here> <Your email address here>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * Nimalia game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);
*/

$this->GOALS = [
  new Goal̤(1, LEVEL_MEDIUM, GOAL_YELLOW),
  new Goal̤(2, LEVEL_DIFFICULT, GOAL_BLUE),
  new Goal̤(3, LEVEL_EASY, GOAL_BLUE),
  new Goal̤(4, LEVEL_EASY, GOAL_YELLOW),
  new Goal̤(5, LEVEL_EASY, GOAL_BLUE),
  new Goal̤(6, LEVEL_MEDIUM, GOAL_YELLOW),
  new Goal̤(7, LEVEL_MEDIUM, GOAL_YELLOW),
  new Goal̤(8, LEVEL_EASY, GOAL_GREEN),
  new Goal̤(9, LEVEL_DIFFICULT, GOAL_BLUE),
  new Goal̤(10, LEVEL_DIFFICULT, GOAL_YELLOW),
  new Goal̤(11, LEVEL_MEDIUM, GOAL_BLUE),
  new Goal̤(12, LEVEL_MEDIUM, GOAL_GREEN),
  new Goal̤(13, LEVEL_EASY, GOAL_RED),
  new Goal̤(14, LEVEL_MEDIUM, GOAL_GREEN),
  new Goal̤(15, LEVEL_DIFFICULT, GOAL_YELLOW),
  new Goal̤(16, LEVEL_MEDIUM, GOAL_RED),
  new Goal̤(17, LEVEL_MEDIUM, GOAL_GREEN),
  new Goal̤(18, LEVEL_MEDIUM, GOAL_RED),
  new Goal̤(19, LEVEL_EASY, GOAL_GREEN),
  new Goal̤(20, LEVEL_EASY, GOAL_RED),
  new Goal̤(21, LEVEL_MEDIUM, GOAL_RED),
  new Goal̤(22, LEVEL_MEDIUM, GOAL_GREEN),
];


/**
 * List of BiomesDescription (cards).
 */
$this->BIOMES_CARDS = [
  1 => [
    0 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    1 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    2 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    3 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    4 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    5 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    6 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    7 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    8 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    9 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    10 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    11 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    12 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    13 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    14 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    15 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    16 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    17 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    18 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    19 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    20 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    21 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    22 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    23 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    24 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    25 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    26 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    27 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    28 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    29 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    30 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    31 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    32 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    33 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    34 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    35 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    36 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    37 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    38 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    39 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    40 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    41 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    42 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    43 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    44 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    45 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    46 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    47 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    48 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    49 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    50 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    51 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    52 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    53 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    54 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    55 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    56 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    57 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    58 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
    59 => new BiomesDescription([new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false), new Biome(LAND_SAVANNAH, ANIMAL_BEAR, false)]),
  ]
];
