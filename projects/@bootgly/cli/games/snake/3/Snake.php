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


class Snake
{
   public Screen $Screen;
   public Food $Food;

   // * Config
   public int $length;
   // Position
   public int $x;
   public int $y;
   //
   // * Data
   public string $direction;
   public array $snake;


   public function __construct (Screen $Screen, Food $Food)
   {
      $this->Screen = $Screen;
      $this->Food = $Food;


      // * Config
      $this->length = 5;
      // Position
      $this->x = 10;
      $this->y = 10;

      // * Data
      // $direction
      $this->direction = '➡️';
      // $snake
      $this->snake = [];
      for ($i = $this->length; $i > 0; $i--) {
         $this->snake[] = [
            $this->x + $i,
            $this->y
         ];
      }
   }

   public function draw ()
   {
      $screen = &$this->Screen->screen;

      $snake = &$this->snake;

      // @ Draw the snake itself
      foreach ($snake as $index => $position) {
         if ($index === 0) {
            $screen[$position[1]][$position[0]] = 'X';
         } else {
            $screen[$position[1]][$position[0]] = 'O';
         }
      }

      // @ Clears the last position of the snake's tail
      $cloaca = end($snake);

      $screen[$cloaca[1]][$cloaca[0]] = ' ';
   }

   public function direct (array $head, string $direction)
   {
      $head = match ($direction) {
         '⬆️' => [$head[0], $head[1] - 1],
         '⬇️' => [$head[0], $head[1] + 1],

         '➡️' => [$head[0] + 1, $head[1]],
         '⬅️' => [$head[0] - 1, $head[1]],

         default => $head
      };

      return $head;
   }
   public function move (string $direction) : bool
   {
      $Screen = $this->Screen;
      $Food = $this->Food;

      $snake = &$this->snake;

      // @ Gets and set the next position of the snake's head
      $head = $this->direct($snake[0], $direction);

      // @ Checks if the snake has collided with the walls or with its own body
      if (
         $head[0] == 0 || $head[0] == $Screen->width - 1
         || $head[1] == 0 || $head[1] == $Screen->height - 1
         || in_array($head, $snake)
      ) {
         return false;
      }

      // @ Inserts the new snake head into the list of positions
      array_unshift($snake, $head);

      // @ Check if the snake has eaten the food
      if ($snake[0][0] == $Food->x && $snake[0][1] == $Food->y) {
         $Food->x = rand(1, $Screen->width - 2);
         $Food->y = rand(1, $Screen->height - 2);
      } else {
         array_pop($snake);
      }

      return true;
   }
}
