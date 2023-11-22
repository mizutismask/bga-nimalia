<?php

/**
 * A BiomesDescription is the graphic representation of a card (informations on it : 4 biomes).
 */
class BiomesDescription {
    public $biomes = [];

    public function __construct(array $biomes) {
        $this->biomes = $biomes;
    }
}

class Biome {
    public int $land;
    public int $animal;
    public bool $river;

    public function __construct(int $land, int $animal, bool $river) {
        $this->land = $land;
        $this->animal = $animal;
        $this->river = $river;
    }
}

/**
 * A BiomesCard is a physical card. It contains informations from matching BiomesDescription, with technical informations like id and location.
 * Location : deck, hand or grid
 * Location arg : order (in deck), playerId (in hand or grid)
 * Type : 1 for simple destination
 * Type arg : the destination type (DestinationCard id)
 */
class BiomeCard extends BiomesDescription {
    public int $id;
    public string $location;
    public int $location_arg;
    public int $type;
    public int $type_arg;

    public function __construct($dbCard, $BIOMES_CARDS) {
        $this->id = intval($dbCard['id']);
        $this->location = $dbCard['location'];
        $this->location_arg = intval($dbCard['location_arg']);
        $this->type = intval($dbCard['type']);
        $this->type_arg = intval($dbCard['type_arg']);
        $biomesDescription = $BIOMES_CARDS[$this->type][$this->type_arg];
        $this->biomes = $biomesDescription->biomes;
    }
}