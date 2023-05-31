<?php
/*
 * --------------------------------------------------------------------------
 * Bootgly PHP Framework
 * Developed by Rodrigo Vieira (@rodrigoslayertech)
 * Copyright 2020-present
 * Licensed under MIT
 * --------------------------------------------------------------------------
 */

namespace projects\Bootgly\CLI\games\Snake;


use Bootgly\CLI\Terminal\Output;


class Display
{
   public Output $Output;

   // * Config
   public int $width;
   public int $height;
   // * Data
   public array $rows; // @ rows { ...columns }


   public function __construct (Output $Output)
   {
      $this->Output = $Output;

      // * Config
      $this->width = 60;  // columns
      $this->height = 20; // rows
      // * Data
      $this->rows = [];
   }

   public function init ()
   {
      $rows = &$this->rows;

      $height = $this->height;
      $width = $this->width;

      for ($y = 0; $y < $height; $y++) {
         for ($x = 0; $x < $width; $x++) {
            if ($x == 0 || $x == $width - 1 || $y == 0 || $y == $height - 1) {
               $rows[$y][$x] = '.';
            } else {
               $rows[$y][$x] = ' ';
            }
         }
      }
   }

   public function render ()
   {
      $Output = $this->Output;

      $rows = $this->rows;

      // @ Output each line
      foreach ($rows as $row) {
         $Output->append(implode('', $row));
      }
   }

   public function end ()
   {
      $this->render();

      // @ Move Cursor to line 20 and column 24 from current position
      $this->Output->Cursor->moveTo(line: 20, column: 24);
      $this->Output->writing('Game over...');
      // @ Move Cursor to outside of display game
      $this->Output->Cursor->down(lines: 11, column: 1);
      // @ Reset Cursor visibility
      $this->Output->Cursor->show();
   }
}
