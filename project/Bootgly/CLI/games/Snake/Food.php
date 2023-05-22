<?php
/*
 * --------------------------------------------------------------------------
 * Bootgly PHP Framework
 * Developed by Rodrigo Vieira (@rodrigoslayertech)
 * Copyright 2020-present
 * Licensed under MIT
 * --------------------------------------------------------------------------
 */

namespace project\Bootgly\CLI\games\Snake;


class Food
{
   public Display $Display;

   // * Data
   public string $food;
   // * Meta
   // ! Display
   // Position
   public int $x;
   public int $y;


   public function __construct (Display $Display)
   {
      $this->Display = $Display;

      // * Data
      $this->food = '*';
      // * Meta
      // ! Display
      // Position
      $this->x = rand(1, $Display->width - 2);
      $this->y = rand(1, $Display->height - 2);
   }

   public function draw ()
   {
      $this->Display->rows[$this->y][$this->x] = $this->food;
   }
}
