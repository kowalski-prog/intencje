<html>
<head>
<link rel="stylesheet" href="styl.css">
</head>
<body>
<?php
session_start();
if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true)) 
{
  header('location: intencje.php');
  exit();
}
?>
  <div class="divlog"><h2>zaloguj się </h2>
<form action="zaloguj.php" method="post"> 
<input class="log" type="text" name="login">
<input class="log" type="password" name="haslo">
<input type="submit" value="zaloguj" >
</form>
<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>
</div>
 
  
</body>
</html>