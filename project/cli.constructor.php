<?php
namespace Bootgly\CLI;

require __DIR__ . '/@bootgly/cli/games/snake/1/Screen.php';
require __DIR__ . '/@bootgly/cli/games/snake/2/Food.php';
require __DIR__ . '/@bootgly/cli/games/snake/3/Snake.php';
require __DIR__ . '/@bootgly/cli/games/snake/4/Game.php';


use Bootgly\CLI;

use Bootgly\CLI\Games\Snake\Screen;
use Bootgly\CLI\Games\Snake\Food;
use Bootgly\CLI\Games\Snake\Snake;
use Bootgly\CLI\Games\Snake\Game;


$Input = CLI::$Terminal->Input;
$Output = CLI::$Terminal->Output;


$Screen = new Screen($Output);
$Food = new Food($Screen);
$Snake = new Snake($Screen, $Food);
$Game = new Game($Screen);


$Input->reading(
   // Terminal Client API
   CAPI: function ($read, $write) // Client Input { $read, $write }
   use ($Output)
   {
      // * Config
      $delay = 100000;
      $throttle = 0.1;
      // * Meta
      // @ ANSI Code
      $decoding = false;
      // * Meta
      // @ Timing
      // last
      $written = 0.0;


      while (true) {
         // @ Autoread each character from Terminal Input (in non-blocking mode)
         // This is as if the terminal input buffer is disabled
         $char = $read(length: 1); // Only length === 1 is acceptable (for now)

         // @ Parse char
         // No data
         if ($char === '') {
            $write(data: ' '); // No effect
            usleep($delay);
            continue;
         }
         // ANSI Code
         if ( $char === "\e" || ($decoding && $char === '[') ) {
            $decoding = true;
            $char = '';
            continue;
         }

         // @ Decode ANSI code
         if ($decoding) {
            $decoded = match ($char) {
               'A' => '⬆️',
               'B' => '⬇️',
               'C' => '➡️',
               'D' => '⬅️',
               default => ''
            };

            $decoding = false;

            $last = microtime(true) - $written;
            if ($last < $throttle) {
               continue;
            }
            $acceleration = 100000;

            $written = microtime(true);
            $write($decoded);
            usleep($delay - $acceleration);
         }
      }
   },
   // Terminal Server API
   SAPI: function ($reading) // Server Input { $reading }
   use ($Output, $Screen, $Food, $Snake, $Game)
   {
      // * Config
      $timeout = 100000; // in microseconds (1 second = 1000000 microsecond)

      // @ Init
      $Output->Cursor->hide();

      $Game->start();

      $continue = true;
      $direction = '➡️';

      // @ Loop
      while (true) {
         // @ Start game
         foreach ($reading(timeout: $timeout) as $data) {
            CLI::$Terminal->clear();

            $Output->render(<<<OUTPUT
            /* @*:
             * @#green: Classic Snake Game - v0.1.0 @;
             * @#yellow: @@ Powered by Bootgly CLI (Bootgly PHP Framework) @;
             * by Rodrigo Vieira [rodrigo@bootly.com]
             * ---
             * @#cyan: Instructions: @;
             * @#cyan: Use the keys ⬆️, ⬇️, ➡️, ⬅️ to control the direction of snake... @;
             * @#cyan: Press and hold any control key above to speed up the snake. @;
             */\n
            OUTPUT);

            // @ Set Snake direction
            if (! $data) {
               break;
            } else if ($data && $data !== ' ') {
               $direction = trim($data);
            }

            // @ Move snake
            $continue = $Snake->move($direction);

            // @ Continue the game?
            if ($continue) {
               $Snake->draw();
               $Food->draw();

               $Screen->render();

               continue;
            }

            // Game over...
            $Screen->render();

            $Output->Cursor->moveTo(line: 20, column: 24);
            $Output->writing('Game over...');
            $Output->Cursor->down(lines: 11, column: 1); // @ Move Cursor to outside of screen game
            $Output->Cursor->show(); // @ Reset Cursor visibility

            break;
         }

         if ($continue) {
            continue;
         }

         break;
      }
   }
);
