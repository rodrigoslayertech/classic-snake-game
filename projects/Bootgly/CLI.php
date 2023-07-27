<?php
namespace Bootgly\CLI;

use Bootgly\CLI;

use projects\Bootgly\CLI\games\Snake\Display;
use projects\Bootgly\CLI\games\Snake\Food;
use projects\Bootgly\CLI\games\Snake\Snake;

// @ Bootgly
$Input = CLI::$Terminal->Input;
$Output = CLI::$Terminal->Output;

// @ Game
$Display = new Display($Output);
$Food = new Food($Display);
$Snake = new Snake($Display, $Food);

// @
$Input->reading(
   // Terminal Client API
   CAPI: function ($read, $write) // Client Input { $read, $write }
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
   use ($Output, $Display, $Food, $Snake)
   {
      // * Config
      $timeout = 100000; // in microseconds (1 second = 1000000 microsecond)

      // @ Bootgly CLI
      $Output->Cursor->hide();

      // @ Game
      $Display->init();

      $continue = true;
      $direction = '➡️';

      // @
      while (true) {
         // @ Start game
         foreach ($reading(timeout: $timeout) as $data) {
            CLI::$Terminal->clear();

            $Output->render(<<<OUTPUT
            /* @*:
             * @#green: Classic Snake Game - v0.1.1 @;
             * @#yellow: @@ Powered by Bootgly CLI (from Bootgly PHP Framework) @;
             * by Rodrigo Vieira [rodrigo@bootly.com]
             * ---
             * @#cyan: Instructions: @;
             * @#cyan: Use the keys ⬆️, ⬇️, ➡️, ⬅️ to control the direction of snake. @;
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

               $Display->render();

               continue;
            }

            // Game over...
            $Display->end();

            break;
         }

         if ($continue) {
            continue;
         }

         break;
      }
   }
);
