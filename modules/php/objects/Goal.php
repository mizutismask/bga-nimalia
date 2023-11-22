<?php

class Goal̤{
    public int $id;
    public int $difficulty;
    public int $color;

    
    public function __construct(int $id, int $difficulty, int $color) {
        $this->id = $id;
        $this->difficulty = $difficulty;
        $this->color = $color;
    }
}