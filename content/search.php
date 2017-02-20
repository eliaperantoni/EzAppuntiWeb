<?php
  session_start();
  $lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
  $id=$_GET['id'];
  $titolo=$_GET['titolo'];
  $autore=$_GET['autore'];
  $dataG=$_GET['data'];
  $tags=$_GET['tags'];
  foreach ($lines as $line) {
    $data = explode(";",$line);
    if($id!= "" && $data[0]!=$id){
      continue;
    }
    if($titolo!="" && !(stripos($data[1], $titolo) !== false)){
      continue;
    }
    if($autore!="" && !(stripos($data[2], $autore) !== false)){
      continue;
    }
    if($dataG!="" && !(stripos(date('d/m/Y H:i:s', floatval($data[3])), $dataG) !== false)){
      continue;
    }
    if($tags!="" && !(stripos($data[7], $tags) !== false)){//TODO Search more than one tag
      continue;
    }
    echo "<tr>";
    foreach ($data as $key => $value) {
      if($key==3){
        printf("<td>%s</td>",date('d/m/Y H:i:s', floatval($value)));
        continue;
      }elseif($key==7){
        echo "<td>";
        foreach (explode(",",$value) as $tag) {
          if($tag != ""){
            printf('<span class="tag is-primary">%s</span>',$tag);
          }
        }
        echo "</td>";
        continue;
      }
      printf("<td>%s</td>",$value);
    }
    if(!($_SESSION['UserData'][3]=='*')){
      $disabled = "is-disabled";
      if($data[2]==$_SESSION['UserData'][1]){
        $disabled = "";
      }
      echo '<td><a class="button is-primary is-small '.$disabled.'"><span class="icon is-small"><i class="fa fa-edit"></i></span><span>Edit</span></a></td>';
      echo '<td><a class="button is-small is-danger '.$disabled.'"><span class="icon is-small"><i class="fa fa-times"></i></span><span>Delete</span></a></td>';
    }

    echo "</tr>";
  }
?>
