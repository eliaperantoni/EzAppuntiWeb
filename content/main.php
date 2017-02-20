<?php
	session_start();
	function goBack(){
		$_SESSION['failed']=1;
		header('Location: index.php');
		exit;
	}
	$lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
	$users = file("../users.txt", FILE_IGNORE_NEW_LINES);
	if(isset($_POST['btnLogin'])){
		//Login attempt
		$success = false;
		$username = $_POST['username'];
		$password = hash('sha256', $_POST['password']);
		foreach ($users as $user) {
			$userData = explode(";",$user);
			if($userData[1]==$username && $userData[2]==$password){
				$success  = true;
				$_SESSION['UserData'] = $userData;
				break;
			}
		}
		if(!$success){
			goBack();
		}else{
			$_SESSION['Logged']='1';
		}
		$_POST['btnLogin'];
	}elseif(isset($_POST['btnRegister'])){
		//Register attempt
		unset($_POST['btnRegister']);
	}elseif($_SESSION['Logged']=='1'){
		//Just let go
	}else{
		goBack();
	}
	if($_POST){
		header("Location: main.php");
		exit;
	}

?>
<!DOCTYPE html>
<head>
<meta name="theme-color" content="#00d1b2">
<title>EzAppunti</title>
<link rel="icon" type="image/png" href="http://bulma.io/favicons/favicon-32x32.png?v=201701041855" sizes="32x32">
<meta charset="UTF-8">
<meta name="description" content="EzAppunti">
<meta name="keywords" content="Ez,Appunti">
<meta name="author" content="Gruppo2-3BI">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.1/css/bulma.css" />
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
</head>
<style>
	#mastertable{
		margin-top: 40px;
		max-width: 80%;
	}
	table,td,tr,th{
		text-align: center;
	}
	.containerTable{
		text-align: center;
	}
	.title{
		margin-top: 80px;
	}
	.nav{
		box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.07);
	}
	.tag{
		margin: 0 3px 3px 0;
	}
	.footer{
		margin-top: 200px;
	}
	#featured{
		float:right;
	}
	@media screen and (max-aspect-ratio: 13/9){
		#featured{
			margin-top: 20px;
		}
		#featuredTitle{
			margin-top: 0px;
		}
	}
	@media screen and (min-aspect-ratio: 13/9){
		.nav{
			position: fixed;
			right: 0;
			left: 0;
		}
		.space{
			height: 49px;
			width: 100%;
		}
	}
</style>

<script>
$(document).on('click','#nav-toggle',function(e){
	$("#nav-menu").toggleClass( "is-active" );
});
$(document).on('click','#logout',function(e){
	window.location.href = 'logout.php';
});
function showResult(){
	var id=document.getElementById("idS").value;
	var titolo=document.getElementById("titoloS").value;
	var autore=document.getElementById("autoreS").value;
	var data=document.getElementById("dataS").value;
	var tags=document.getElementById("tagsS").value;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      $('#tbody').html($(this.responseText));
    }
  }
	xmlhttp.open("GET","search.php?id="+id+"&titolo="+titolo+"&autore="+autore+"&data="+data+"&tags="+tags,true);
  xmlhttp.send();

}
window.onload = function() {
  showResult();
};
</script>
<nav class="nav">
  <div class="nav-left">
    <a class="nav-item">
      <img src="logo.png" alt="EzAppunti logo">
    </a>
		<a id="logout" class="nav-item">
      Logout
    </a>

  </div>

  <div class="nav-center">
    <a target="_blank" href="https://github.com/hellix08/EzAppunti" class="nav-item">
      <span class="icon">
        <i class="fa fa-github"></i>
      </span>
    </a>
    <a target="_blank" class="nav-item">
      <span class="icon">
        <i class="fa fa-android"></i>
      </span>
    </a>
  </div>

  <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
  <!-- You need JavaScript to toggle the "is-active" class on "nav-menu" -->
  <span id="nav-toggle" class="nav-toggle">
    <span></span>
    <span></span>
    <span></span>
  </span>

  <!-- This "nav-menu" is hidden on mobile -->
  <!-- Add the modifier "is-active" to display it on mobile -->
  <div id="nav-menu" class="nav-right nav-menu">
		<a href="main.php" class="nav-item is-tab is-active">
      Home
    </a>
    <a class="nav-item is-tab">
      About Us
    </a>
    <a class="nav-item is-tab">
      Docs
    </a>

    <span style="" class="nav-item">

      <a class="button is-primary">
        <span class="icon">
          <i class="fa fa-download"></i>
        </span>
        <span>Desktop Download</span>
      </a>
    </span>
  </div>
</nav>
<div class="space"></div>
<section class="hero is-primary">
  <div class="hero-body">
    <div class="container">
			<div style="float:left">
      <h1 id="featuredTitle" class="title">
        Featured Note
      </h1>
      <h2 class="subtitle">
        <?php printf("By %s",$_SESSION['UserData'][1]);?>
      </h2></div>
			<div id="featured" class="card">
  <header class="card-header">
    <p class="card-header-title">
      Il PHP è carino :)
    </p>
  </header>
  <div class="card-content">
    <div class="content">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nec iaculis mauris.<br>
      <a class="authorLink">@Hellix</a>. <a class="tagLink">#php</a> <a class="tagLink">#vettori</a>
      <br>
      <small>11:09 PM - 1 Jan 2016</small>
    </div>
  </div>
  <footer class="card-footer">
    <a class="card-footer-item"><span class="icon"><i class="fa fa-thumbs-up"></i></span></a>
    <a class="card-footer-item"><span class="icon"><i class="fa fa-thumbs-down"></i></span></a>
  </footer>
</div>
    </div>
  </div>
</section>
<div class="containerTable">
<h1 class="title is-1">Appunti</h1>
<table class="table is-striped" id="mastertable" align="center">
	<thead>
		<th><input id="idS" onkeyup="showResult()" class="input" type="text" placeholder="Id"></th>
		<th><input id="titoloS" onkeyup="showResult()"  class="input" type="text" placeholder="Titolo"></th>
		<th><input id="autoreS" onkeyup="showResult()" class="input" type="text" placeholder="Autore"></th>
		<th><input id="dataS" onkeyup="showResult()" class="input" type="text" placeholder="Data"></th>
		<th></td><td></td><td></td>
		<th><input id="tagsS" onkeyup="showResult()" class="input" type="text" placeholder="Tags"></th>
		<?php
			if(!($_SESSION['UserData'][3]=='*')){
				echo "<td></td><td></td>";
			}
		 ?>

	</thead>
	<tbody id="tbody">
 </tbody>
</table>
</div>
<footer class="footer">
  <div class="container">
    <div class="content has-text-centered">
      <p>
        <strong>EzAppunti</strong> by Elia Perantoni, Loris Pesarin, Riccardo Lupo, Andrea Lotesto.
      </p>
      <p>
        <a class="icon" target="_blank" href="https://github.com/hellix08/EzAppunti" >
          <i class="fa fa-github"></i>
        </a>
				<a class="icon" target="_blank" href="" >
          <i class="fa fa-android"></i>
        </a>
				<a class="icon" target="_blank" href="" >
          <i class="fa fa-bug"></i>
        </a>
      </p>
    </div>
  </div>
</footer>
