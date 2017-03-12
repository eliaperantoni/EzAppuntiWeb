<?php
  $lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
  $i=0;
  foreach ($lines as $key => $value) {
    if($_GET['id']==explode(";",$value)[0]){
      $old = explode(";",$value);
      $out=$old[0].";".$_GET['titolo'].";".$old[2].";".$old[3].";".$old[4].";".$old[5].";".$old[6].";".$_GET['tags'];
      $lines[$i]=$out;
    }
    $i=$i+1;
  }
  file_put_contents("../master.txt", implode(PHP_EOL, $lines));
 ?>
