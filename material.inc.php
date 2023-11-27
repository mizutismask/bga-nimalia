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
    0 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN)]),
    1 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_OTTER, RIVER_UP), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_FLAMINGO)]),
    2 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    3 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_LION)]),
    4 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_LION), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PINGUIN)]),
    5 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE)]),
    6 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_LION)]),
    7 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO)]),
    8 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    9 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_PINGUIN)]),
    10 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR)]),

    11 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    12 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_LION), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO)]),
    13 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    14 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    15 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    16 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
    17 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    18 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    19 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),
    20 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR)]),
    21 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_LION)]),

    22 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN)]),
    23 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_FLAMINGO)]),
    24 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN)]),
    25 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO)]),
    26 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA)]),
    27 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
    28 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_CROCODILE)]),
    29 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    30 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR)]),
    31 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),
    32 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),

    33 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    34 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    35 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_GORILLA)]),
    36 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    37 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    38 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR)]),
    39 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO)]),
    40 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_LION), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA)]),
    41 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_CROCODILE)]),
    42 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN)]),
    43 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO)]),

    44 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    45 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_PINGUIN)]),
    46 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_LION), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR)]),
    47 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA)]),
    48 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),
    49 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION)]),
    50 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    51 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_LION), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_CROCODILE)]),
    52 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_CROCODILE)]),
    53 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_BEAR)]),
    54 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_LION)]),

    55 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    56 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_LION), new Biome(ANIMAL_FLAMINGO)]),
    57 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA)]),
    58 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    59 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
  ]
];
