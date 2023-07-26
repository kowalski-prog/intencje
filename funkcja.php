<?php
session_start();
$_SESSION['funkcja']= "0";
	require_once "connect.php";

	$polaczenie = mysqli_connect($host, $db_user, $db_password, $db_name);
	$termin = $_SESSION['termin'];
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		
		if($_SESSION["termin2"] == "sunday" || $_SESSION["nazwa_holy"]!=="" ) {
            $daty=array_column($_SESSION["holidays"], 0);
			$nazwa_holy=array_column($_SESSION["holidays"],1);
			$_SESSION["nazwa_holy"] ="";
    		 for($i=0;$i<=(count($_SESSION["holidays"])-1);$i++){
       			 if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty[$i])){
       				 $_SESSION["nazwa_holy"] = $nazwa_holy[$i];
       					 break;
       				 }    
    			}	
			
   
   $licznik=0;
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["4"]) && $_SESSION["popasterce"]==1) {$_SESSION["n_msza1"]="0.00";}
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["0"]) && $_SESSION["posylwestrze"]==1){ $licznik=1;}
	if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["6"]) && $_SESSION["popaschalnej"]==1) { $licznik=1;}
			
			for ($i=1+$licznik; $i<=$_SESSION["ile_niedziela"]+$licznik; $i++){
				$intencja= "int".$i;
				$stypendium="styp".$i;
				$godzina="n_msza".$i;
				$_SESSION[$intencja]= mysqli_real_escape_string($db_conn, $_POST[$intencja]);
				$_SESSION[$stypendium]=  mysqli_real_escape_string($db_conn, $_POST[$stypendium]);
				
           }
		   for ($c=1; $c<=$_SESSION["ile_ksiezy"]; $c++){
		$query_wpis = mysqli_query($db_conn, "SELECT * FROM celebrans".$c." WHERE termin ='$termin'");
		$ile_terminow = $query_wpis->num_rows;
		



		if($ile_terminow>0)    //update
		{    $x="";
			$i=0;
			
			for($a=$c+$licznik; $a<=$_SESSION["ile_niedziela"]+$licznik; $a+=$_SESSION["ile_ksiezy"]){
				if ($a<$_SESSION["ile_ksiezy"]) {$coma=",";}else{$coma="";}
				$godzina="n_msza".$a;
				$intencja="int".$a;
				$stypendium="styp".$a;
				$i++;
				
				$x.=" godzina".$i."= '$_SESSION[$godzina]',
				intencja".$i."='$_SESSION[$intencja]',
				stypendium".$i."='$_SESSION[$stypendium]'".$coma;}
		
			
			$formula=" UPDATE celebrans".$c."
			SET termin='$termin', ".$x." WHERE termin ='$termin';";
			
				mysqli_query($db_conn, "$formula");
				
				
			echo $formula;
			$_SESSION["wynik"]="Zapisano pod datą: ".$termin;
			header('Location: kwerenda.php');
			
			}
  
			 elseif ($ile_terminow==0){					//insert
		
				$formula="";
				$a=0;
				$formula_into="";
				$formula_val="";
				for($i=$c; $i<=$_SESSION["ile_niedziela"]; $i+=$_SESSION["ile_ksiezy"]){
					if ($a==0 && $c!==$_SESSION["ile_niedziela"]) {$coma=",";}else{$coma="";}
				$intencja= "int".$i+$licznik;
				$stypendium="styp".$i+$licznik;
				$godzina="n_msza".$i+$licznik;
					$a++;
				$formula_into=$formula_into. " godzina".$a.", intencja".$a.", stypendium".$a."$coma";
				$formula_val=$formula_val."'$_SESSION[$godzina]','$_SESSION[$intencja]','$_SESSION[$stypendium]'$coma";
				
			} echo " INSERT INTO celebrans".$c." (termin,".rtrim($formula_into,",").") VALUES('$termin',".rtrim($formula_val, ",").")  ";
				$formula=" INSERT INTO celebrans".$c." (termin,".rtrim($formula_into,",").") VALUES('$termin',".rtrim($formula_val, ",").")  ";
			
			mysqli_query($db_conn, $formula);
			echo $formula;
			
			$_SESSION["wynik"]="Zapisano pod datą: ".$termin;
				header('Location: kwerenda.php');
			
			 }}}
		//------------------------------------------------------------------------------------------------	
		
		
		else {	
			//dni powszednie 
			$one="0";
			if ($_SESSION["ile_mszy"]==1){$one=1;}
			for ($i=1; $i<=($_SESSION["ile_ksiezy"]*2); $i++){
					$intencja= "int".$i;
					$stypendium="styp".$i;
					$godzina="pow_msza".$i;
					$_SESSION[$intencja]= mysqli_real_escape_string($db_conn, $_POST[$intencja]);
					$_SESSION[$stypendium]=  mysqli_real_escape_string($db_conn, $_POST[$stypendium]);
				   }
			for ($c=1; $c<=$_SESSION["ile_ksiezy"]; $c++){
				   $query_wpis = mysqli_query($db_conn, "SELECT * FROM celebrans".$c." WHERE termin ='$termin'");
		$ile_terminow = $query_wpis->num_rows;
		
		if($ile_terminow>0)    //update
		{ 
			$c2= $c+$_SESSION["ile_ksiezy"];
			$intencja1= "int".$c;
			$stypendium1="styp".$c;
			$intencja2= "int".$c+$_SESSION["ile_ksiezy"];
			$stypendium2="styp".$c+$_SESSION["ile_ksiezy"];


			
			$g=$_SESSION["ile_mszy"]/($_SESSION["ile_ksiezy"]*2);
			$xd1= $c*floatval($g);
			$xd2= $c2*floatval($g);
			for ($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
				if($xd1>$i-1 && $xd1<=$i ) $godz1=$i;
				if($xd2>$i-1 && $xd2<=$i ) $godz2=$i;
			}
				$godzina1="pow_msza".$godz1;
				$godzina2="pow_msza".$godz2;



			$sql="UPDATE celebrans$c 
			SET termin='$termin', godzina1= '$_SESSION[$godzina1]',intencja1= '$_SESSION[$intencja1]', stypendium1= '$_SESSION[$stypendium1]',
			 godzina2= '$_SESSION[$godzina2]',intencja2= '$_SESSION[$intencja2]', stypendium2= '$_SESSION[$stypendium2]' WHERE termin ='$termin'";
			mysqli_query($db_conn, $sql);
			echo $sql;


			$_SESSION["wynik"]="Zapisano pod datą: ".$termin;
			header('Location: kwerenda.php');

			
			}
		
  
			 else {					//insert
		
				$c2= $c+$_SESSION["ile_ksiezy"];
				$intencja1= "int".$c;
				$stypendium1="styp".$c;
				$intencja2= "int".$c+$_SESSION["ile_ksiezy"];
				$stypendium2="styp".$c+$_SESSION["ile_ksiezy"];


				
				$g=$_SESSION["ile_mszy"]/($_SESSION["ile_ksiezy"]*2);
				$xd1= $c*floatval($g);
				$xd2= $c2*floatval($g);
				for ($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
					if($xd1>$i-1 && $xd1<=$i ) $godz1=$i;
					if($xd2>$i-1 && $xd2<=$i ) $godz2=$i;
				}
					$godzina1="pow_msza".$godz1;
					$godzina2="pow_msza".$godz2;


	
				$sql="INSERT INTO celebrans".$c." (termin, godzina1, intencja1, stypendium1, godzina2, intencja2, stypendium2) 
				VALUES('$termin','$_SESSION[$godzina1]','$_SESSION[$intencja1]','$_SESSION[$stypendium1]', '$_SESSION[$godzina2]', '$_SESSION[$intencja2]', '$_SESSION[$stypendium2]')";
				mysqli_query($db_conn, $sql);
				$_SESSION["wynik"]="Zapisano pod datą:  ".$termin;
				header('Location: kwerenda.php');
			}
				
			}//
		}
			
				
		
		$polaczenie->close();
	}
	
	
?>