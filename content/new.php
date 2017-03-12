<?php
session_start();
  $lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
  $max=-1;
  foreach ($lines as $key => $value) {
    if(intval(explode(";",$value)[0])>$max){
      $max=intval(explode(";",$value)[0]);
    }
  }
  $id=$max+1;
  $out=strval($id).";".$_GET['titolo'].";".$_SESSION['UserData'][1].";"."1486469828.806685;0;0;0".";".$_GET['tags']."\n";
  array_push($lines,$out);
  $f = fopen("../data/".strval($id).".txt", "w");
  fwrite($f, $_GET['content']);
  fclose($f);
  file_put_contents("../master.txt", implode(PHP_EOL, $lines));
 ?>
