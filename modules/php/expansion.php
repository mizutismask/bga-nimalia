<?php

trait ExpansionTrait {

    /**
     * List the biomes cards that will be used for the game.
     */
    function getBiomesToGenerate() {
        $biomesCards = [];
        $expansion = EXPANSION;

        switch ($expansion) {
            default:
                foreach ($this->BIOMES_CARDS[1] as $typeArg => $card) {
                    $biomesCards[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
        }

        return $biomesCards;
    }

    /**
     * Return the number of biomesCards cards shown at the beginning.
     */
    function getInitialBiomesCardNumber(): int {
        switch (EXPANSION) {
            default:
                return 3;
        }
    }
}
