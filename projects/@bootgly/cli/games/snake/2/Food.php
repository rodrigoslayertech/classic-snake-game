<?php
/*
 * --------------------------------------------------------------------------
 * Bootgly PHP Framework
 * Developed by Rodrigo Vieira (@rodrigoslayertech)
 * Copyright 2020-present
 * Licensed under MIT
 * --------------------------------------------------------------------------
 */

namespace Bootgly\CLI\Games\Snake;


class Food
{
   public Screen $Screen;

   // * Data
   public string $food;
   // * Meta
   // ! Screen
   // Position
   public int $x;
   public int $y;


   public function __construct (Screen $Screen)
   {
      $this->Screen = $Screen;

      // * Data
      $this->food = '*';
      // * Meta
      // ! Screen
      // Position
      $this->x = rand(1, $Screen->width - 2);
      $this->y = rand(1, $Screen->height - 2);
   }

   public function draw ()
   {
      $this->Screen->screen[$this->y][$this->x] = $this->food;
   }
}
