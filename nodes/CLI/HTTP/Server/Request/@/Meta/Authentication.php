<?php
/*
 * --------------------------------------------------------------------------
 * Bootgly PHP Framework
 * Developed by Rodrigo Vieira (@rodrigoslayertech)
 * Copyright 2020-present
 * Licensed under MIT
 * --------------------------------------------------------------------------
 */

namespace Bootgly\CLI\HTTP\Server\Request\_\Meta;


use Bootgly\CLI\HTTP\Server\Request\_\Meta;


final class Authentication
{
   public Meta $Meta;


   public function __construct (Meta $Meta)
   {
      $this->Meta = $Meta;
   }
}
