<?php
session_start();
if (isset($_SESSION["wynik"])==true && isset($_SESSION["satis"])==true) {unset($_SESSION["wynik"]);unset($_SESSION["satis"]); }
$_SESSION['funkcja']= "0";
	require_once "connect.php";

	$polaczenie = mysqli_connect($host, $db_user, $db_password, $db_name);
	

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		if(isset($_POST["termin"])==true) {$termin = mysqli_real_escape_string($db_conn, $_POST["termin"]);
			$_SESSION['termin']= $termin;}
		else 
		{$termin=$_SESSION['termin'];}
        
      $x= $y= "";

	 for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){			//intencja 1 /
		$x.=" celebrans".$i.".intencja1,";
	 }
	 for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){			//intencja 2
		$x.=" celebrans".$i.".intencja2,";

	 }
	 for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){			
		$x.=" celebrans".$i.".stypendium1,";
	 }
	 for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){			
		if ($i< $_SESSION["ile_ksiezy"]) {$coma=",";} else {$coma="";}
		$x.=" celebrans".$i.".stypendium2".$coma;
	 }
	 for($i=2; $i<=$_SESSION["ile_ksiezy"]; $i++){
		$y.="INNER JOIN celebrans".$i." ON celebrans".$i.".termin = celebrans1.termin ";
	 }
	
	 
	 $sql="SELECT celebrans1.termin, $x
	   FROM celebrans1 $y  where celebrans1.termin='$termin';";
	   
		
		$query_wpis = mysqli_query($db_conn, "$sql");
		$liczba_kolumn=mysqli_field_count($db_conn);
		$ile_terminow = $query_wpis->num_rows; 
	
		if($ile_terminow>0)
		{

  		 $record = mysqli_fetch_array($query_wpis);
				$_SESSION['termin'] = $record['termin'];
			
				for($i=1; $i<=($liczba_kolumn-1); $i++){
			
			$_SESSION['parametr'.$i] = $record[$i];
			}
				
		
				
			header('Location: intencje.php');
               
        }
        else
        { 
            
            $_SESSION['funkcja']= "2";
			for($i=1; $i<=($liczba_kolumn-1); $i++){
				$_SESSION['parametr'.$i] = "";}
           header('Location: intencje.php');
           
        }
    }
?>
