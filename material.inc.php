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
    1 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN)]),
    2 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_FLAMINGO)]),
    3 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    4 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_LION)]),
    5 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_LION), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PINGUIN)]),
    6 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE)]),
    7 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_LION)]),
    8 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO)]),
    9 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    10 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_PINGUIN)]),

    11 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR)]),
    12 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    13 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_LION), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO)]),
    14 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    15 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    16 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    17 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
    18 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    19 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    20 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),

    21 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR)]),
    22 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_LION)]),
    23 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN)]),
    24 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_FLAMINGO)]),
    25 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PINGUIN)]),
    26 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO)]),
    27 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA)]),
    28 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
    29 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_CROCODILE)]),
    30 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    
    31 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR)]),
    32 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),
    33 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    34 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    35 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_CROCODILE)]),
    36 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_GORILLA)]),
    37 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PANDA)]),
    38 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    39 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR)]),
    40 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_FLAMINGO)]),
    
    41 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_LION), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA)]),
    42 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_CROCODILE)]),
    43 => new BiomesDescription([new Biome(ANIMAL_LION), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN)]),
    44 => new BiomesDescription([new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO)]),
    45 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_LION), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN)]),
    46 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_PINGUIN)]),
    47 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_LION), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_BEAR)]),
    48 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_GORILLA)]),
    49 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_PINGUIN)]),
    50 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_DOWN), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION)]),
    
    51 => new BiomesDescription([new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    52 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_LION), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_CROCODILE)]),
    53 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_UP), new Biome(ANIMAL_CROCODILE)]),
    54 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_LION), new Biome(ANIMAL_BEAR)]),
    55 => new BiomesDescription([new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_LION)]),
    56 => new BiomesDescription([new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_PANDA), new Biome(ANIMAL_OTTER, LAND_SNOW, RIVER_DOWN)]),
    57 => new BiomesDescription([new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_GIRAFFE), new Biome(ANIMAL_LION), new Biome(ANIMAL_FLAMINGO)]),
    58 => new BiomesDescription([new Biome(ANIMAL_PANDA), new Biome(ANIMAL_FLAMINGO), new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_GORILLA)]),
    59 => new BiomesDescription([new Biome(ANIMAL_OTTER, LAND_SAVANNAH, RIVER_DOWN), new Biome(ANIMAL_GORILLA), new Biome(ANIMAL_OTTER, LAND_JUNGLE, RIVER_UP), new Biome(ANIMAL_GIRAFFE)]),
    60 => new BiomesDescription([new Biome(ANIMAL_CROCODILE), new Biome(ANIMAL_BEAR), new Biome(ANIMAL_PINGUIN), new Biome(ANIMAL_CROCODILE)]),
  ]
];
