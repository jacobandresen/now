<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class Encoding{
  public static function isUTF8($string)
  {
    $c=0; $b=0;
    $bits = 0;
    $len = strlen($string);
    for($i=0;$i<$len; $i++)
    {
      $c=ord($string[$i]);
      if ($c > 128) {
        if ($c >= 254) return false;
        elseif($c >= 252) $bits = 6;
        elseif($c >= 248) $bits = 5;
        elseif($c >= 240) $bits = 4;
        elseif($c >= 224) $bits = 3;
        elseif($c >= 192) $bits = 2;
        else return false;
        if (($i+$bits) > $len) return false;
        while($bits > 1){
          $i++;
          $b=ord($str[$i]);
          if ($b < 128 || $b > 191) return false;
          $bits--;
        }
      }
    }
    return true;
  }
};
?>
