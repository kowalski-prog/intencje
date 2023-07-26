<?php
	session_start();
    $_SESSION["strona"]++;
        $host1 = 'localhost';
        $db_user1 ='komarkus_intencje';
        $db_password1 =  'Intencje123**';
        $db_name1 =  'komarkus_'.$_SESSION["parafia"];
        $db_conn1 = mysqli_connect($host1,$db_user1,$db_password1) 
        or die ("Odpowiedź: Błąd połączenia z serwerem $host1");
        mysqli_select_db($db_conn1, $db_name1) or die("Trwa konserwacja bazy danych… Odśwież stronę za kilka sekund.");
    //}
       
       // if (isset($_SESSION["strona"])!==true && ){
        //----------------------------------tabele celebransów------------
       $sql1="";
        for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
          $sql1="  CREATE TABLE `celebrans$i` (
                `termin` date NOT NULL,
                `godzina1` varchar(5) NOT NULL,
                `intencja1` text NOT NULL,
                `stypendium1` int(5) NOT NULL,
                `godzina2` varchar(5) DEFAULT NULL,
                `intencja2` text DEFAULT NULL,
                `stypendium2` int(5) DEFAULT NULL,
                UNIQUE KEY `termin` (`termin`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
               mysqli_query($db_conn1, $sql1);
        }
            
        //------------------------------------użytkownicy---------------------
        $sql2=  "CREATE TABLE `users` (
            `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_fullname` varchar(128) NOT NULL,
            `user_email` varchar(128) DEFAULT NULL,
            `user_passwordhash` varchar(255) NOT NULL,
            `autorised` int(1) NOT NULL,
            PRIMARY KEY (`user_id`),
            UNIQUE KEY `user_email` (`user_email`)
           ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
           mysqli_query($db_conn1, $sql2);
        //---------------------------------------------- gregorianka-----------------   
       $licznik=0;
       $widok="`celebrans1`.`termin` AS `termin`, ";
        for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++) {
            for ($a=1; $a<=2; $a++){
                $licznik++;
                if ($i==1){
                $widok.=" `celebrans$i`.`intencja$a` AS `i$licznik`
                         , `celebrans$i`.`stypendium$a` AS `s$licznik`, ";}
                         elseif ($i>1) 
                         $widok.=" `celebrans$i`.`intencja$a` AS `i$licznik`
                         , `celebrans$i`.`stypendium$a` AS `s$licznik`,";

            }
        }

        $join="";
        for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
            if ($i==1)
            $join.= "celebrans$i "; 
            elseif ($i>1) 
            $join.="join `celebrans$i` 
            on `celebrans".($i-1)."`.`termin` = `celebrans$i`.`termin`";
        }

           $sql4= "CREATE VIEW `gregorianka` AS select 
            ".rtrim($widok,",")."
           from $join";
        
        echo $sql4;
         mysqli_query($db_conn1, $sql4);
        //----------------------------------ustawienia-------------------------
        $msza_niedzielna ="";
        $msza_powszednia ="";
        $msza_wigilijna ="";
        $msza_znies ="";
        $odpust ="";
        $ipsadie="";
        $tytul="";
        $wolne="";
        
        for($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
            $msza_niedzielna.= "`n_msza$i` varchar(5) NOT NULL,";
        }
        for($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
            $msza_powszednia .=" `pow_msza$i` varchar(5) NOT NULL,";
        }
        for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
            $msza_wigilijna .=" `wig_msza$i` varchar(5) NOT NULL,";
        }
        for($i=1; $i<=$_SESSION["ile_znies"]; $i++){
            $msza_znies .=" `msza_znies$i` varchar(5) NOT NULL,";
        }
        for($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
            $odpust .=" `odpust$i` varchar(5) NOT NULL,";
            $ipsadie.=" `ipsadie$i` varchar(7) NOT NULL,";
            $tytul.=" `tytul$i` TEXT NOT NULL,";
        }
       
        for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
            $wolne.=" `wolne$i` varchar(15) NOT NULL,";
        }
       
       
        $sql3 = "CREATE TABLE `ustawienia` (
            `ID` int(1) NOT NULL DEFAULT 1,
            `ile_ksiezy` int(5) NOT NULL,
            `ile_mszy` int(5) NOT NULL,
            `ile_mszy_niedziela` int(5) NOT NULL,
            `ile_mszy_wigilia` int(5) NOT NULL,
            `ile_odpustow` varchar(5) NOT NULL,
            `ile_znies` varchar(5) NOT NULL,
            `pascha` varchar(5) NOT NULL,
            $msza_wigilijna
            $msza_powszednia
            $msza_niedzielna
            $msza_znies
            $odpust
            $ipsadie
            $tytul
            `prime_holy` varchar(5) NOT NULL,
            `sezon` varchar(5) NOT NULL,
            `zm_lato` varchar(5) NOT NULL,
            `zm_zima` varchar(5) NOT NULL,
            `zm_godz` varchar(5) NOT NULL,
            $wolne
            `roraty` varchar(5) NOT NULL,
            `godz_roraty` varchar(5) NOT NULL,
           
            UNIQUE KEY `ID` (`ID`)
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
           mysqli_query($db_conn1, $sql3);

if (isset($_POST["gwiazdka"])==true && $_POST["gwiazdka"]=="on") $gwiazdka = 1; else $gwiazdka = 0 ;
if (isset($_POST["nowy_rok"])==true && $_POST["nowy_rok"]=="on") $nowy_rok = 1; else $nowy_rok = 0 ;
if (isset($_POST["wielkanoc"])==true && $_POST["wielkanoc"]=="on") $wielkanoc = 1; else $wielkanoc = 0 ;
if (isset($_POST["koledy"])==true && $_POST["koledy"]=="on") $koledy = 1; else $koledy = 0 ;
if (isset($_POST["lato-zima"])==true && $_POST["lato-zima"]=="on") $lato_zima = 1; else $lato_zima = 0 ;
if (isset($_POST["g_roraty"])==true && $_POST["g_roraty"]!=="") $g_roaty =$_POST["g_roraty"]; else $g_roraty=""; 
if (isset($_POST["roraty_godz"])==true && $_POST["g_roraty"]!=="") $g_roaty =$_POST["g_roraty"]; else $g_roraty="";  
$sezon = $koledy.$lato_zima;
$prime_holy = $gwiazdka.$nowy_rok.$wielkanoc;


$msze_niedzielna=$msze_powszednia=$msze_wigilijna=$msze_znies=$odpusty= $wolni= $tytuly= $ipsadiem="";

for($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
    $msze_niedzielna.= "'".$_SESSION["n_msza$i"]."',";
}
for($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
    $msze_powszednia .="'".$_SESSION["pow_msza$i"]."',";
}
for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
    $msze_wigilijna .="'".$_POST["wigilia$i"]."',";
}
for($i=1; $i<=$_SESSION["ile_znies"]; $i++){
    $msze_znies .="'".$_SESSION["msza_znies$i"]."',";
}
for($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
    if ($_POST["odpust_ruch$i"]=="ruch") {$odpusty .="'".$_POST["ruch$i"]."',"; $_POST["tytul$i"]=$_POST["ruch$i"];} else {$odpusty .="'".$_POST["odpustd$i"]."',";}
    $ipsadiem .="'".$_POST["odpust$i"]."',"; 
    $tytuly .=  "'".$_POST["tytul$i"]."',";
    
}

for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
    $wolni .="'".$_SESSION["wolne$i"]."',";   
}




$sql5 = "INSERT INTO `ustawienia` 
VALUES ('1','".$_SESSION["ile_ksiezy"]."','".$_SESSION["ile_mszy"]."','".$_SESSION["ile_niedziela"]."','".$_SESSION["ile_wigilia"]."','".$_SESSION["ile_odpustow"]."','".$_SESSION["ile_znies"]."','".$_POST["pascha"]."', $msze_wigilijna $msze_powszednia $msze_niedzielna $msze_znies  $odpusty $ipsadiem $tytuly '$prime_holy','$sezon', '".$_POST["zm_lato"]."','".$_POST["zm_zima"]."','".$_POST["zm_godz"]."', ".$wolni."'".$_POST["roraty"]."','".$_POST["g_roraty"]."')";
echo $sql5;
mysqli_query($db_conn1, $sql5);

//-------------------------------------conn file--------------------------------
$conn_php = fopen("connect.php", "w") or die("Unable to open file!");
$txt = "<?php

"."$"."host = '$host1';
"."$"."db_user ='$db_user1';
"."$"."db_password = '$db_password1';
"."$"."db_name = '$db_name1';
"."$"."db_conn = mysqli_connect("."$"."host,"."$"."db_user,"."$"."db_password) 
or die ('Odpowiedź: Błąd połączenia z serwerem "."$"."host');
mysqli_select_db("."$"."db_conn, "."$"."db_name) or die('Trwa konserwacja bazy danych… Odśwież stronę za kilka sekund.');
?> ";
fwrite($conn_php, $txt);

fclose($conn_php);


//}

header('location: install.php');


?>      