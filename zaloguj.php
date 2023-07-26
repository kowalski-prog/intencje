<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: log.php');
		exit();
	}

	require_once "connect.php";

	$polaczenie = mysqli_connect($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");

		
		
		$query_login = mysqli_query($db_conn, "SELECT * FROM users WHERE user_fullname ='$login'");
		$ilu_userow = $query_login->num_rows;
		if($ilu_userow>0)
		{
  		 $record = mysqli_fetch_assoc($query_login);
  		 $hash = $record["user_passwordhash"];
		 $id =$record["user_id"];
		}
  			 if (password_verify($haslo, $hash))
			  {
				$_SESSION['login'] = $login;
				$_SESSION['id'] = $id;
				$_SESSION['zalogowany'] = true;
				unset($_SESSION['blad']);
				header('Location: intencje.php');
				
			} else {
				
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: log.php');
				
			}
			
		
	
		$polaczenie->close();
	}
	
?>