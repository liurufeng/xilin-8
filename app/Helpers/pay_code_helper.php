<?php

function get_code($input)
{
  $str_arr = str_split(strtolower($input));
  $code = 0;

  foreach($str_arr as $k => $v) {
    $code += ($k+1) * ord($v);
  }

  return str_pad('' . $code % 10000, 4, "8", STR_PAD_LEFT);
}