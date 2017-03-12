<?php
  session_start();
  if($_SESSION && isset($_SESSION['Logged']) && $_SESSION['Logged']=='1' && isset($_SESSION['UserData'])){
    header("Location: main.php");
		exit;
  }
?>
<head>
<meta name="theme-color" content="#00d1b2">
<title>EzAppunti</title>
<link rel="icon" type="image/png" href="http://bulma.io/favicons/favicon-32x32.png?v=201701041855" sizes="32x32">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.1/css/bulma.css" />
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
</head>
<style>
  body{
    text-align: center;
    padding-top: 20px;
  }
  form{
    margin: 0 auto 50px auto;
    display: inline-block;
    width: 80%;
  }
  @media screen and (min-aspect-ratio: 13/9) {
    .failed{
      width: 20%;
    }
    body{
      padding-top: 80px;
    }
    form{
      width: 20%;
    }
	}
  .form-container{
    text-align: center;
  }
  .failed{
    display: inline-block;
    visibility:hidden;
    <?php if($_SESSION['failed']==1){
      echo "visibility:visible;";
      $_SESSION['failed']=0;
    } ?>
  }
  button{
    width: 100%;
  }
  .bug{
    position: fixed;
    bottom: 20px;
    right:20px;
  }
  @media screen and (max-aspect-ratio: 13/9) {
		.bug{
		  position: relative;
      bottom: 20px;
      right:auto;
		}
	}
</style>
<script>
$(document).on('click','#loginButton',function(e){
	$("#loginButton").addClass( "is-loading" );
});
$(document).on('click','#regButton',function(e){
	$("#regButton").addClass( "is-loading" );
});
</script>
<h1 class="title is-1">EzAppunti</h1>
<h4 class="subtitle is-4">Login or Register</h4>
<div class="failed box notification is-danger">
  Wrong username or password!
</div>
<div class="form-container">
  <form action="main.php" method="post">
    <p class="control">
      <input class="input" type="text" placeholder="Username" name="username">
    </p>
    <p class="control">
      <input class="input" type="password" placeholder="Password" name="password">
    </p>
    <p class="control">
      <button id="loginButton" type="submit" class="button is-primary" name="btnLogin">Login</button>
    </p>
  </form>
<div class="form-container">
</div>
  <form action="main.php" method="post">
    <p class="control">
      <input class="input is-disabled" type="text" placeholder="Username" name="Rusername">
    </p>
    <p class="control">
      <input class="input is-disabled" type="password" placeholder="Password" name="Rpassword">
    </p>
    <p class="control">
      <input class="input is-disabled" type="password" placeholder="Repeat password" name="Rrepeatpassword">
    </p>
    <p class="control">
      <button id="regButton" type="submit" class="button is-primary is-disabled" name="btnRegister">Register</button>
    </p>
  </form>
</div>
<a class="bug icon" target="_blank" href="https://github.com/hellix08/EzAppuntiWeb/issues/new" alt="Report Bug">
  <i class="fa fa-bug"></i>
</a>
