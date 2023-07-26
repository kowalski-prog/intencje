<?php

$host = 'localhost';
$db_user ='parafia_bobola';
$db_password = 'parafia123';
$db_name = 'parafia1';
$db_conn = mysqli_connect($host,$db_user,$db_password) 
or die ('Odpowiedź: Błąd połączenia z serwerem $host');
mysqli_select_db($db_conn, $db_name) or die('Trwa konserwacja bazy danych… Odśwież stronę za kilka sekund.');
?> 