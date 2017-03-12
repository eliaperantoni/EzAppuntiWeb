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
	if($_GET && isset($_GET['q']) && $_GET['q']=="delete"){
		$lines = file("../master.txt", FILE_IGNORE_NEW_LINES);
		foreach ($lines as $key => $line) {
			$lineData = explode(";",$line);
			if($lineData[0]==$_GET['id']){
				unset($lines[$key]);
				file_put_contents("../master.txt", implode(PHP_EOL, $lines));
			}
		}
		unset($_GET);
		header("Refresh:0");
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
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.js"></script>
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
		margin-top: 30px;
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
	#noteContent{
		background-color: white;
		padding: 20px;
		border-radius: 5px;
	}
</style>

<script>
var notes;
var results;
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

			notes =  this.responseText.split("%raw%")[1].split("\n");

			
      $('#tbody').html(this.responseText.split("%raw%")[0]);
			$.get(
					"content.php",
					{id : notes[notes.length-1].split(";")[0]},
					function(data) {
						var d = new Date(parseFloat(notes[notes.length-1].split(";")[3]) * 1000);
						$("#lastDate").html(d.toTimeString());
							$("#lastNote").html(data.substring(0,300)+"..."+"<br>@"+notes[notes.length-1].split(";")[2]);
					}
			);

			$('.deleteEz').click(function(event) {
					var id= jQuery(this).attr("id");
					$.get(
					    "main.php",
					    {q:'delete',id : id},
					    function(data) {
									location.reload(true);
					    }
					);
			});
			$('.note').click(function(event) {
					$("#contentModal").toggleClass("is-active");
					var id = jQuery(this).attr("id");
					$.get(
							"content.php",
							{id : id},
							function(data) {
									$("#effectiveNoteContent").html(data);
							}
					);
			});
			$('.editEz').click(function(event) {
				$("#mode").val("edit");
				$("#modalTitle").html("Modifica appunto");
					var id= jQuery(this).attr("id");
					$("#rawId").val(id);
					$("#modal").addClass("is-active");
					for (var i = 0; i < notes.length; i++) {
						if(notes[i].split(";")[0]==id){
							$("#titoloEdit").val(notes[i].split(";")[1]);
							$("#tagsEdit").val(notes[i].split(";")[7]);
							$.get(
							    "content.php",
							    {id : id},
							    function(data) {
											$("#contenutoEdit").val(data);
							    }
							);
						}
					}

			});
			$("#closeModal").click(function(event){
				$("#contentModal").toggleClass("is-active");
			});
			$('.closeEdit').click(function(event) {
					var id= jQuery(this).attr("id");
					$("#modal").removeClass("is-active");

			});
			$('.saveEdit').click(function(event) {
				if($("#mode").val()=="edit"){
					$.get(
							"save.php",
							{id : $("#rawId").val(),titolo :$("#titoloEdit").val(),tags: $("#tagsEdit").val(),content:$("#contenutoEdit").val()},
							function(data) {
								location.reload(true);
							}
					);
				}else{
					$.get(
							"new.php",
							{titolo :$("#titoloEdit").val(),tags: $("#tagsEdit").val(),content:$("#contenutoEdit").val()},
							function(data) {
								location.reload(true);
							}
					);
				}
			});
			$('#newNote').click(function(event) {
				$("#mode").val("new");
					$("#modal").addClass("is-active");
					$("#modalTitle").html("Crea nuovo appunto");
					$("#titoloEdit").val("");
					$("#tagsEdit").val("");
					$("#contenutoEdit").val("");
			});

    }
  }
	xmlhttp.open("GET","search.php?id="+id+"&titolo="+titolo+"&autore="+autore+"&data="+data+"&tags="+tags,true);
  xmlhttp.send();

}
window.onload = function() {
  showResult();
};

</script>
<div id="rawId" style="visibility:hidden"></div>
<div id="mode" style="visibility:hidden"></div>
<div id="contentModal" class="modal">
  <div class="modal-background"></div>
  <div id="noteContent" class="modal-content">
    <p id="effectiveNoteContent">
		</p>
  </div>
  <button id="closeModal" class="modal-close"></button>
</div>
<div id="modal" class="modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p id="modalTitle" class="modal-card-title">Modifica appunto</p>
      <button class="closeEdit delete"></button>
    </header>
    <section class="modal-card-body">
      <form action="" method="post">
				<input id="titoloEdit" style="margin-bottom:10px" name="editTitolo" placeholder="Titolo" type="text" class="input"></input>
				<input id="tagsEdit" style="margin-bottom:10px" name="editTags" placeholder="Tags" type="text" class="input"></input>
				<textarea id="contenutoEdit" name="contenuto" placeholder="Contenuto" type="text" class="textarea"></textarea>
			</form>
    </section>
    <footer class="modal-card-foot">
      <a class="saveEdit button is-success">Salva modifiche</a>
      <a class="closeEdit button">Annulla</a>
    </footer>
  </div>
</div>
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
        Benvenuto su EzAppunti
      </h1>
      <h2 class="subtitle">
        Gli appunti nel cloud
      </h2></div>
			<div id="featured" class="card" style="width:60%">
  <header class="card-header">
    <p class="card-header-title">
      Appunto più recente
    </p>
  </header>
  <div class="card-content">
    <div class="content" >
			<span id="lastNote"></span><br>
      <br>
      <small id="lastDate">11:09 PM - 1 Jan 2016</small>
    </div>
  </div>
  <footer class="card-footer">
    <!--<a class="card-footer-item"><span class="icon"><i class="fa fa-thumbs-up"></i></span></a>
    <a class="card-footer-item"><span class="icon"><i class="fa fa-thumbs-down"></i></span></a>-->
  </footer>
</div>
    </div>
  </div>
</section>
<div class="containerTable">
	<button class="button is-primary" style="margin-top:60px" id="newNote">Scrivi appunto</button>
<h1 class="title is-1">Appunti</h1>

<table class="table is-striped" id="mastertable" align="center">
	<thead>
		<th><input id="idS" onkeyup="showResult()" class="input" type="text" placeholder="Id"></th>
		<th><input id="titoloS" onkeyup="showResult()"  class="input" type="text" placeholder="Titolo"></th>
		<th><input id="autoreS" onkeyup="showResult()" class="input" type="text" placeholder="Autore"></th>
		<th><input id="dataS" onkeyup="showResult()" class="input" type="text" placeholder="Data"></th>

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
        <strong>EzAppunti</strong> is made with <span style="margin-top:4px;color:#00d1b2" class="icon is-small">
  <i class="fa fa-heart"></i>
</span> by Elia Perantoni, Loris Pesarin, Riccardo Lupo, Andrea Lotesto.
      </p>
      <p>
        <a class="icon" target="_blank" href="https://github.com/hellix08/EzAppunti" >
          <i class="fa fa-github"></i>
        </a>
				<a class="icon" target="_blank" href="" >
          <i class="fa fa-android"></i>
        </a>
				<a class="icon" target="_blank" href="https://github.com/hellix08/EzAppuntiWeb/issues/new" >
          <i class="fa fa-bug"></i>
        </a>
      </p>
    </div>
  </div>
</footer>
