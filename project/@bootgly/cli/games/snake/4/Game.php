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


class Game
{
   public Screen $Screen;

   // * Config
   // * Data
   // * Meta


   public function __construct (Screen $Screen)
   {
      $this->Screen = $Screen;

      // * Config
      // * Data
      // * Meta
   }

   public function start ()
   {
      // ! Screen
      $screen = &$this->Screen->screen;
      $height = $this->Screen->height;
      $width = $this->Screen->width;

      for ($y = 0; $y < $height; $y++) {
         for ($x = 0; $x < $width; $x++) {
            if ($x == 0 || $x == $width - 1 || $y == 0 || $y == $height - 1) {
               $screen[$y][$x] = '.';
            } else {
               $screen[$y][$x] = ' ';
            }
         }
      }
   }
}
