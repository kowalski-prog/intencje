<?php

$host = 's149.cyber-folks.pl';
$db_user ='komarkus_intencje';
$db_password = 'Intencje123**';
$db_name = 'komarkus_intencje';
$db_conn = mysqli_connect($host,$db_user,$db_password) 
or die ('Odpowiedź: Błąd połączenia z serwerem $host');
mysqli_select_db($db_conn, $db_name) or die('Trwa konserwacja bazy danych… Odśwież stronę za kilka sekund.');



    $name= mysqli_real_escape_string($db_conn, "Jakub");
    $password = mysqli_real_escape_string($db_conn, "Banaszak");
    $parafia = mysqli_real_escape_string($db_conn, "intencje");
    $pass = password_hash($password, PASSWORD_DEFAULT);
    echo "INSERT INTO konta (name, pass) VALUES ('$name', '$pass')";
    if (mysqli_query($db_conn, "INSERT INTO konta (name, pass, parafia) VALUES ('$name', '$pass', '$parafia')"))
    {
       echo "Rejestracja przebiegła poprawnie";
      
    } else{
       echo "Nieoczekiwany błąd - użytkownik już istnieje lub błąd serwera MySQL.";
    }
 
?> 