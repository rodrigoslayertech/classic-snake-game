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


use Bootgly\CLI\Terminal\Output;


class Screen
{
   public Output $Output;

   // * Config
   public int $width;
   public int $height;
   // * Data
   public array $screen; // @ rows { ...columns }


   public function __construct (Output $Output)
   {
      $this->Output = $Output;

      // * Config
      $this->width = 60;  // columns
      $this->height = 20; // rows
      // * Data
      $this->screen = [];
   }

   public function render ()
   {
      $Output = $this->Output;

      $screen = $this->screen;

      // @ Output each line
      foreach ($screen as $row) {
         $Output->append(implode('', $row));
      }
   }
}
