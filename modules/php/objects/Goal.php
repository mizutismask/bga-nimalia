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
}