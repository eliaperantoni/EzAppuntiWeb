<?php
  $lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
  $id=$_GET['id'];
  foreach ($lines as $key => $value) {
    if(explode(";",$value)[0]==$id){
      echo file_get_contents('../data/'.$id.'.txt');
    }
  }
 ?>
