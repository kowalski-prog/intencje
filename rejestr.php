<?php
require_once "connect.php";
session_start();
$_SESSION["strona"]++;

for ($i=0; $i<=3; $i++){
   $user_fullname= mysqli_real_escape_string($db_conn, $_POST["login".$i]);
   $user_password = mysqli_real_escape_string($db_conn, $_POST["pass".$i]);
   $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
   
   if (mysqli_query($db_conn, "INSERT INTO users (user_fullname, user_email, user_passwordhash, autorised) VALUES ('$user_fullname', null, '$user_password_hash', '1')")){
      echo "Rejestracja przebiegła poprawnie";
      $_SESSION["strona"]=6;
      header("location: install.php");
   } else{
      echo "Nieoczekiwany błąd - użytkownik już istnieje lub błąd serwera MySQL.";
   }
}



 ?>