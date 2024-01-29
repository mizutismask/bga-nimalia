<?php

class Goal̤{
    public int $id;
    public int $level;
    public int $color;

    public function __construct(int $id, int $level, int $color) {
        $this->id = $id;
        $this->level = $level;
        $this->color = $color;
    }

    public static function getCastedGoal($jsonDecoded):Goal̤ {
        $instance = new self( $jsonDecoded["id"], $jsonDecoded["level"], $jsonDecoded["color"]);
        return $instance;
    }
}