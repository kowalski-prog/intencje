<?php
session_start();
if (!isset($_SESSION['termin'])) $_SESSION['termin']=date("Y-m-d");
$termin = $_SESSION['termin'];
$_SESSION["dni_tygodnia"] = array( 'Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota' );
$datarr = date_create($termin);
$day= date_format($datarr, "w" );
require "connect.php";
$polaczenie = mysqli_connect($host, $db_user, $db_password, $db_name);
if (!isset($_SESSION['zalogowany']))
{
    header('location: log.php');
    exit();
}
else
{  
    if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$query_wpis = mysqli_query($db_conn, "SELECT * FROM ustawienia WHERE ID ='1'");
		$ustawienia = $query_wpis->num_rows;
		if($ustawienia>0)
        $record = mysqli_fetch_assoc($query_wpis);
        $_SESSION["ile_ksiezy"] = $record['ile_ksiezy'];
        $_SESSION["ile_mszy"] = $record['ile_mszy'];
        $_SESSION["ile_niedziela"] =$record['ile_mszy_niedziela'];
        $_SESSION["ile_wigilia"] =$record['ile_mszy_wigilia'];
        $_SESSION["godz_roraty"] =$record['godz_roraty'];
        $_SESSION["ile_odpustow"] =$record['ile_odpustow'];
        $_SESSION["ile_znies"] =$record['ile_znies'];
        $_SESSION["prime_holy"] = $record['prime_holy'];
        $_SESSION["sezon"] = $record['sezon'];
        $_SESSION["zm_lato"] = $record['zm_lato'];
        $_SESSION["zm_zima"] = $record['zm_zima'];
        $_SESSION["zm_godz"] = $record['zm_godz'];
        $_SESSION["pascha"] = $record['pascha'];
        $_SESSION["roraty"] = $record['roraty'];
        for($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
        $powszednia="pow_msza".$i;
        $_SESSION[$powszednia] =$record[$powszednia];}

        for($i=1; $i<=$_SESSION["ile_znies"]; $i++){
            $msza_znies="msza_znies".$i;
            $_SESSION[$msza_znies] =$record[$msza_znies];}

        for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
            $wigilijna="wig_msza".$i;
            $_SESSION[$wigilijna] =$record[$wigilijna];}
            
        for($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
        $niedzielna="n_msza".$i;
            $_SESSION[$niedzielna] =$record[$niedzielna];}

            for($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
            $odpusty="odpust".$i;
            $ipsadie="ipsadie".$i;
            $porz_odp="porz_odp".$i;
            $tytul="tytul".$i;
            $_SESSION[$odpusty] =$record[$odpusty];
            $_SESSION[$ipsadie] = $record[$ipsadie];
            $_SESSION[$porz_odp]= $record[$porz_odp];
            $_SESSION[$tytul]= $record[$tytul];
        }
        for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
            $wolne= "wolne".$i;
            $_SESSION[$wolne] =$record[$wolne];
        }
    }
}
?>
<html>
<head>
<meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Księga Intencji</title>
<link rel="stylesheet" href="styl.css">
<script>
    function stypendia(){
    document.getElementById("lewy").innerHTML='<form action="intencje.php" method="get">'+
'<input type="date" name="pocz" value="<?php echo @$_GET['pocz']; ?>">'+
    '<input type="date" name="koniec" value="<?php echo @$_GET['koniec']; ?>"> <br><br><input type="submit" value="sprawdź"></form>';
}    
function gregorianum(){
    document.getElementById("lewy").innerHTML='wybierz możliwy termin początkowy:<br><form action="intencje.php" method="get">'+
'<input type="date" name="greg_termin" value="<?php echo @$_GET['greg_termin']; ?>">'+
   '<br><br><input type="submit" value="sprawdź"></form>';
}    
function cito(){
    document.getElementById("lewy").innerHTML='Najbliższy termin Mszy św. od daty:<br><form action="intencje.php" method="get">'+
'<input type="date" name="cito_termin" value="<?php echo @$_GET['cito_termin']; ?>">'+
   '<br><br><input type="submit" value="sprawdź"></form>';}

function wynik(){
    document.getElementById("wynik").innerHTML=""; 
    val = window.clearInterval(val);
}
      
      val = setInterval('wynik()','2000');
	    
      
     
function free(x, y){
    if (document.getElementById(x).disabled==true) {
        document.getElementById(x).disabled=false;
        document.getElementById(y).disabled=false;
    }
    else if (document.getElementById(x).disabled==false){
        document.getElementById(x).disabled=true;
        document.getElementById(y).disabled=true;
    }
}
function freeblock(){
    for (i=1; i<=20; i++){
        var x = "int"+i;
         y= "styp"+i;
        if (document.getElementById(x).value=="free"){
            document.getElementById(x).disabled=true;
            document.getElementById(y).disabled=true;
        }
       
    }
}
function ustawienia(){
  document.getElementById("lewy").innerHTML= '<object type="text/html" data="setup.php" style="overflow:auto; width:100%; height:100%"></object>';
   


}    
function dataPlus() {
    document.getElementById("da").stepUp(1);
    document.getElementById("formularz").submit();
}
    function dataMinus() {
    document.getElementById("da").stepDown(1);
    document.getElementById("formularz").submit();
}

function scrollout() {
    if (localStorage.getItem("skrol") !== null){
        var x = localStorage.getItem("skrol");
        window.scrollTo(0, x);
    }
}
    function scrollin() {
    var y= window.scrollY;
    localStorage.setItem("skrol", y);
    }

</script>


</head>
<body  onunload="scrollin();" onload="scrollout();" >
<?php
if (isset($_SESSION["wynik"])==true) $_SESSION["satis"]=1;
$Y=date('Y');
$y=date('Y',strtotime($termin));
$easterDate  = easter_date($y);
$easterDay   = date('j', $easterDate);
$easterMonth = date('n', $easterDate);
$year   = date('Y', $easterDate);
$gwiazdka = strtotime("25.12.".$Y);
$lastadvent = strtotime("last sunday", $gwiazdka);
$familia = strtotime("next sunday",$gwiazdka);
$zmienna1="";



$_SESSION["holidays"] = array(
  array(mktime(0, 0, 0, 01,  01,  $y),"Uroczystość Świętej Bożej Rodzicielki<br>"),   //0
  array(mktime(0, 0, 0, 1,  6,  $y), "Uroczystość Objawienia Pańskiego<br>"),        //1
  array(mktime(0, 0, 0, 8,  15, $y),"Uroczystość Wniebowzięcia NMP<br>" ),           //2
  array(mktime(0, 0, 0, 11, 1,  $y), "Uroczystość Wszystkich Świętych<br>"),         //3
  array( mktime(0, 0, 0, 12, 25, $y),"Uroczystość Bożego Narodzenia<br>"),           //4
  array( mktime(0, 0, 0, 12, 26, $y),"II Dzień Świąt Bożego Narodzenia, św. Szczepana<br>"),    //5
  array(mktime(0, 0, 0, $easterMonth, $easterDay,  $year), "Uroczystość Zmartwychwstania Pańskiego<br>" ),//6
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $year), "Poniedziałek Wielkanocny<br>"), //7
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 42, $year), "Uroczystość Wniebowstąpienia Pańskiego<br>"),//8
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 49, $year), "Uroczystość Zesłania Ducha Świętego<br>"),     //9
  array(strtotime("-28 days",$lastadvent), "Uroczystość Chrystusa, Króla Wszechświata"),//10
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 56, $year), "Uroczystość Trójcy Przenajświętszej<br>"),//11
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 60, $year), "Uroczystość Bożego Ciała<br>"),//12
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 7, $year), "Niedziela Palmowa<br>"),//13
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 14, $year), "V Niedziela Wielkiego Postu<br>"),//14
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 21, $year), "IV  Niedziela Wielkiego Postu<br>"),//15
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 28, $year), "III Niedziela Wielkiego Postu<br>"),//16
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 35, $year), "II Niedziela Wielkiego Postu<br>"),//17
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 42, $year), "I Niedziela Wielkiego Postu<br>"),//18
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 7, $year), "Niedziela Miłosierdzia Bożego<br>"),//19
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 14, $year), "III Niedziela Wielkanocna<br>"),//20
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 21, $year), "IV Niedziela Wielkanocna<br>"),//21
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 28, $year), "V Niedziela Wielkanocna<br>"),//22
  array(mktime(0, 0, 0, $easterMonth, $easterDay + 35, $year), "VI Niedziela Wielkanocna<br>"),//23
  array($lastadvent, "IV Niedziela Adwentu<br>"),                                           //24
  array(strtotime("-7 days",$lastadvent), "III Niedziela Adwentu<br>"),             //25
  array(strtotime("-14 days",$lastadvent), "II Niedziela Adwentu<br>"),             //26
  array(strtotime("-21 days",$lastadvent), "I Niedziela Adwentu<br>"),              //27
  array($familia, "Niedziela Świętej Rodziny<br>"),                                 //28
  array(strtotime("+8 days",$familia), "Niedziela Chrztu Pańskiego<br>"),         //29
  //-30--V-----------------------------Święta Zniesione-----------------------------------------------//34-42
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 3, $year), "Wielki Czwartek"),
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 2, $year), "Wielki Piątek"),
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 1, $year), "Wielka Sobota // Wigilia Paschalna"),
  array(mktime(0, 0, 0, 12, 24, $y), "Wigilia Bożego Narodzenia"),
  array(mktime(0, 0, 0, $easterMonth, $easterDay - 46, $year), "Środa Popielcowa"),
  array(mktime(0, 0, 0, $easterMonth, $easterDay +50, $year), "NMP Matki Kościoła<br>"),//10
  array(mktime(0, 0, 0, 3, 19, $y), "Uroczystość św. Józefa"),
  array(mktime(0, 0, 0, 3, 25, $y), "Uroczystość Zwiastowania Pańskiego"),
  array(mktime(0, 0, 0, 2, 2, $y), "Uroczystość Ofiarowania Pańskiego"),
  array(mktime(0, 0, 0, 5, 3, $y), "Uroczystość NMP Królowej Polski"),
  array(mktime(0, 0, 0, 6, 29, $y), "Uroczystość świętych Piotra i Pawła"),
  array(mktime(0, 0, 0, 12, 8, $y), "Uroczystość Niepokalanego Poczęcia NMP"),
  array(mktime(0, 0, 0, 8, 6, $y), "Święto Przemienienia Pańskiego"),
  array(mktime(0, 0, 0, 2, 11, $y), "NMP z Lourdes - Światowy Dzień Chorego"),
  array(mktime(0, 0, 0, 4, 24, $y), "Uroczystość św. Wojciecha, głównego Patrona Polski"),
  array(mktime(0, 0, 0, 5, 16, $y), "św. Andrzeja Boboli, Patrona Polski")
);

for ($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
if (strlen($_SESSION["odpust$i"]<=5)){
if ($_SESSION[$ipsadie]=="ipsadie"){
    $dzieno1=substr($_SESSION["odpust$i"], 0, 2);
    $miesiaco1=substr($_SESSION["odpust$i"], 3, 5);
    array_push($_SESSION["holidays"], array(mktime(0, 0, 0, $miesiaco1, $dzieno1, $y), $_SESSION["tytul$i"]));
}

else if($_SESSION[$ipsadie]=="niedz+"){
    $dataodp= strtotime($_SESSION["odpust$i"]."-".$y);
    array_push($_SESSION["holidays"], array(strtotime("next sunday", $dataodp), $_SESSION["tytul$i"]));
}
else if($_SESSION[$ipsadie]=="niedz-"){
    $dataodp= strtotime($_SESSION["odpust$i"]."-".$y);
    array_push($_SESSION["holidays"], array(strtotime("last sunday", $dataodp), $_SESSION["tytul$i"]));
}
    }

}

$daty=array_column($_SESSION["holidays"], 0);
$nazwa_holy=array_column($_SESSION["holidays"],1);

$_SESSION["nazwa_holy"] ="";
$_SESSION["nazwa_zniesione"] ="";
$_SESSION["odpust"]="";
$a=0;
     for($i=0;$i<=32;$i++){     // Święta w porządku niedzielnym
        if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty[$i])){
        $_SESSION["nazwa_holy"] = $nazwa_holy[$i];
             for($oi=1; $oi<=$_SESSION["ile_odpustow"]; $oi++){
            $odpust = "odpust".$oi;
            if($_SESSION[$odpust]==$nazwa_holy[$i]) $_SESSION["odpust"] = "Odpust Parafialny";
            }
        break;}
        }    
        for($i=34;$i<=42;$i++){
            if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty[$i])){ // dni liturgiczne o porządku zniesionym
            $_SESSION["nazwa_zniesione"] = $nazwa_holy[$i];
            $_SESSION["zniesione"]=true; 
            break;} else {$_SESSION["zniesione"]=false;
            }
        }
        for($i=43;$i<=45;$i++){                   // Wspomnienia i uroczystości
            if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty[$i])){
                $_SESSION["nazwa_zniesione"] = $nazwa_holy[$i];
                $_SESSION["zniesione"]=true; 
                break;}
         }
         for($i=46;$i<=(count($_SESSION["holidays"])-1);$i++){                  // Odpusty parafialne
            if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty[$i])){
                $_SESSION["nazwa_zniesione"] = $nazwa_holy[$i];
                $_SESSION["odpust"] = "Odpust Parafialny";
                break;}
    }
  //------------------------------------------------------------------------------------ wyjątki
    $styczen1 =mktime(0, 0, 0, 1, 1, $y);
   $styczen31 =mktime(0, 0, 0, 1, 31, $y);
   $licznik=0;
   $_SESSION["popasterce"]=substr($record["prime_holy"], 0, 1);
   $_SESSION["posylwestrze"]=substr($record["prime_holy"], 1, 1);;
   $_SESSION["popaschalnej"]=substr($record["prime_holy"], 2, 1);;
   $_SESSION["sezon_koledowy"]=substr($_SESSION["sezon"],0,1) ;
   $_SESSION["lato_zima"]= substr($_SESSION["sezon"],1,1);
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["4"])&& $_SESSION["popasterce"]==1) {$_SESSION["n_msza1"]="0.00";} // Pasterka
   
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["33"])) {              // Wigilia Bożego Narodzenia
        for ($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
            $_SESSION["pow_msza$i"]= $record["wig_msza$i"]  ;
        }}
        $zamiana="pow_msza".$_SESSION["ile_mszy"];
        
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["0"])&& $_SESSION["posylwestrze"]==1) { $_SESSION["ile_niedziela"]-=1;$licznik=1;} //Nowy Rok
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["30"])) {$_SESSION["n_msza1"]="18.00";} //Msza Wieczerzy
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["32"])) {$_SESSION["n_msza1"]=$record["pascha"];} // Wigilia Paschalna
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["6"]) && $_SESSION["popaschalnej"]==1) {$_SESSION["ile_niedziela"]-=1;$licznik=1;} // Wielkanoc
    if (strtotime($termin) < $daty["4"] && strtotime($termin) > $daty["27"]) {$_SESSION["roraty_time"]=1;} else{$_SESSION["roraty_time"]=0;} // roraty 4-gwiazdka
   if ($_SESSION["sezon_koledowy"]==1 && strtotime($termin) < $styczen31 && strtotime($termin) > $styczen1) {$_SESSION["ile_mszy"]-1;} // zmiany koledowe
   if ($_SESSION["lato_zima"]==1 && strtotime($termin) > strtotime($record["zm_lato"]."-".$y) && strtotime($termin) < strtotime($record["zm_zima"]."-".$y)) {
            
            $_SESSION[$zamiana]=$record[$zamiana];}

            
     //  ----------------------------------------------------------------------------plansza
   function plansza($termin){
    $licznik=1;
    $datarr = date_create($termin);
        $day= date_format($datarr, "w" );
        $termin = strtotime($termin);
        $termin = date("l", $termin);
        $_SESSION["termin2"] = strtolower($termin);
        $styp=(2*$_SESSION["ile_ksiezy"]);
        $cel=1;
        if($_SESSION["termin2"] == "sunday" || $_SESSION["nazwa_holy"]!=="" || $_SESSION["odpust"] =="Odpust Parafialny") {
          
            echo("<table align='center' border='0' heigh='100px' border='3px' style='border: 3px solid white'>");
            for ($i=1; $i<=($_SESSION["ile_niedziela"]); $i++){
           global  $licznik;
           $licznik++;
            $styp++;
            echo('<tr><td><p>'.$_SESSION["n_msza".$licznik].'</p></td><td>
            <input type="text" class="input" name="int'.$licznik.'" placeholder="wpisz intencję" value="'.@$_SESSION["parametr".$i].'">
            </td><td><input type="number" name="styp'.$licznik.'" id="styp'.$licznik.'"  step="10" placeholder="ofiara" value="'.@$_SESSION["parametr".$styp].'"></td></tr>'); }
            echo("</table>");

        } else  if ($_SESSION["nazwa_zniesione"] !==""){
            $tablica = array();
            for ($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
                $powszednia="pow_msza".$i;
                array_push($tablica, $_SESSION[$powszednia]);
            }
             for ($i=1; $i<=$_SESSION["ile_znies"]; $i++){
                $znies="msza_znies".$i;
                array_push($tablica, $_SESSION[$znies]);
                
             }
            sort($tablica);
            echo("<table align='center' heigh='100px' border='3px' style='border: 3px solid white'>");
            foreach($tablica as $i){
                 echo('<tr><td><p>'. $i.'</p></td><td>');
            for($a=1; $a<=($_SESSION["ile_ksiezy"]*2)/count($tablica); $a++){
                $free='"int'.$licznik.'","styp'.$licznik.'"';
               $styp++;
               if ($_SESSION["dni_tygodnia"][$day]==$_SESSION["wolne".$a] && $_SESSION["parametr".$licznik]==""){
                echo('<div ondblclick=free('.$free.') > <input type="text" class="input" name="int'.$licznik.'" id="int'.$licznik.'" placeholder="wpisz intencję celebrans'.$cel.'" disabled value="'.@$_SESSION["parametr".$licznik].'">
                <input type="number" name="styp'.$licznik.'" id="styp'.$licznik.'" placeholder="ofiara" step="10" disabled value="'.@$_SESSION["parametr".$styp].'"></div>');
               }
               else{
             echo('<div ondblclick=free('.$free.') > <input type="text" class="input" name="int'.$licznik.'" id="int'.$licznik.'" placeholder="wpisz intencję celebrans'.$cel.'" value="'.@$_SESSION["parametr".$licznik].'">
            <input type="number" name="styp'.$licznik.'" id="styp'.$licznik.'" placeholder="ofiara" step="10"  value="'.@$_SESSION["parametr".$styp].'"></div>');}
               
            $cel++;
            $licznik++;
            if ($cel>$_SESSION["ile_ksiezy"]) $cel=1;
             }
            echo("</td></tr>");
             }
               echo("</table>");
            
            }
            else
            {
         
           
                if ($_SESSION["ile_mszy"]<2){
            echo("<table align='center' heigh='100px' border='3px' style='border: 3px solid white'>");
            if($_SESSION["roraty"]=="dod" && $_SESSION["roraty_time"]==1){
                echo '<tr><td><p>'. $_SESSION["godz_roraty"].'</p></td><td><input type="text" class="input" name="int2" placeholder="wpisz intencję" value="">
                <input type="number" name="styp1"id="styp'.$licznik.'"  step="10" placeholder="ofiara" value=""></td></tr><br>';}
            for ($i=1; $i<=($_SESSION["ile_mszy"]); $i++)
            {echo('<tr><td><p>'. $_SESSION["pow_msza".$i].'</p></td><td>');
                 if($_SESSION["roraty"]=="roraty_godz"){$_SESSION["pow_msza1"]=$_SESSION["godz_roraty"];}
               for($a=1; $a<=$_SESSION["ile_ksiezy"]; $a++){
                $styp++;
           echo(' <input type="text" class="input" name="int'.$a.'" id="int'.$a.'" placeholder="wpisz intencję celebrans'.$cel.'" value="'.@$_SESSION["parametr".$a].'" ondblclick="free("int'.$a.'", "styp'.$a.'")">
           <input type="number" name="styp'.$a.'" id="styp'.$licznik.'" placeholder="ofiara"  step="10" value="'.@$_SESSION["parametr".$styp].'" id="styp'.$a.'"><br>');
           $cel++;
               }
            echo("</td></tr>");
            
            }
            echo("</table>");}

       
           else
           {
          
            
           echo("<table align='center' heigh='100px' border='3px' style='border: 3px solid white'>");
           if($_SESSION["roraty"]=="dod" && $_SESSION["roraty_time"]==1){
               echo '<tr><td><p>'. $_SESSION["godz_roraty"].'</p></td><td><input type="text" class="input" name="int2" placeholder="wpisz intencję" value="">
               <input type="number" name="styp1" id="styp'.$licznik.'"  step="10" placeholder="ofiara" value=""></td></tr><br>';}
            for ($i=1; $i<=($_SESSION["ile_mszy"]); $i++)
                 {echo('<tr><td><p>'. $_SESSION["pow_msza".$i].'</p></td><td>');
                if($_SESSION["roraty"]=="roraty_godz"){$_SESSION["pow_msza1"]=$_SESSION["godz_roraty"];}
            for($a=1; $a<=($_SESSION["ile_ksiezy"]*2)/$_SESSION["ile_mszy"]; $a++){
                $free='"int'.$licznik.'","styp'.$licznik.'"';
               $styp++;
               if ($_SESSION["dni_tygodnia"][$day]==$_SESSION["wolne".$a] && @$_SESSION["parametr".$licznik]==""){
                echo('<div ondblclick=free('.$free.') > <input type="text" class="input" name="int'.$licznik.'" id="int'.$licznik.'" placeholder="wpisz intencję celebrans'.$cel.'" disabled value="'.@$_SESSION["parametr".$licznik].'">
                <input type="number" name="styp'.$licznik.'" id="styp'.$licznik.'"  step="10" placeholder="ofiara" disabled value="'.@$_SESSION["parametr".$styp].'"></div>');
               }
               else{
             echo('<div ondblclick=free('.$free.') > <input type="text" class="input" name="int'.$licznik.'" id="int'.$licznik.'" placeholder="wpisz intencję celebrans'.$cel.'" value="'.@$_SESSION["parametr".$licznik].'">
            <input type="number" name="styp'.$licznik.'" id="styp'.$licznik.'" placeholder="ofiara"  step="10" value="'.@$_SESSION["parametr".$styp].'"></div>');}
            $cel++;
            $licznik++;

            if ($cel>$_SESSION["ile_ksiezy"]) $cel=1;
            }
           echo("</td></tr>");
           }
           echo("</table>");
           
           
        }}
       
        }

function planszaone($termin){
    $styp=(2*$_SESSION["ile_ksiezy"]);
    for ($i=1; $i<=$_SESSION["ile_ksiezy"];$i++){
        echo("<table align='center' border='0'>");
        echo('<tr><td><p>'. $_SESSION["n_msza1"].' </p></td><td>
        <input type="text" class="input" name="int'.$i.'" placeholder="wpisz intencję" value="'.@$_SESSION["parametr".$i].'">
        </td><td><input type="number" name="styp'.$i.'" value="'.@$_SESSION["parametr".$styp].'"></td></tr>'); 
        echo("</table>"); 
    }
}

function intencja(){
    if (isset($_GET["pocz"])==true) {unset($_GET["pocz"]); unset($_GET["koniec"]);}
    header("location: intencje.php");}

function gregorianka($x){
    require "connect.php";
    $int_greg=$_GET["int_greg"];
    while($gregoriana = mysqli_fetch_array($query_wpis)) {
        for($i=1; $i=($_SESSION['ile_ksiezy']*2); $i++)
       if ( $gregoriana[$i]="") {mysqli_query($db_conn, "UPDATE intencje SET i".$i."='$x'"); break;}
    }
}



 ?> 

<div class="naglowek"><br><br><br><br><h1>Księga intencji</h1></div>
<br>
<div class="grid-container">
  <div class="lewa" id="lewy">



<?php 

//---------------------------------------------------------------------podsumowanie stypendium
if (isset($_GET["pocz"])==true && isset($_GET["koniec"])==true){
echo '<form action="intencje.php" method="get">
<input type="date" name="pocz" value="'. $_GET["pocz"].'">
    <input type="date" name="koniec" value="'. $_GET["koniec"].'"> <br><br> <input type="submit" value="sprawdź"></form>';
                $termins1=@$_GET['pocz']; 
                $termins2=@$_GET['koniec']; 
              
        for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
            $primacja="celebrans".$i."1";
            $binacja="celebrans".$i."2";
                $sql="SELECT SUM(stypendium1) FROM celebrans".$i." where termin between'$termins1' and '$termins2';";
            $query_records1 = mysqli_query($db_conn, "$sql");
            $_SESSION[$primacja] = mysqli_fetch_array($query_records1);
            $sql="SELECT SUM(stypendium2) FROM celebrans".$i." where termin between'$termins1' and '$termins2';";
            $query_records2 = mysqli_query($db_conn, "$sql");
            $_SESSION[$binacja] = mysqli_fetch_array($query_records2);
            } 
            $suma_celebrans=0;
            for($c=1; $c<=$_SESSION["ile_ksiezy"]; $c++){
                $primacja="celebrans".$c."1";
                $binacja="celebrans".$c."2"; 
            echo "<br> <table class='lewa' align='center' border=0>
            <tr><td>Celebrans ".$c.":       </td><td>". ($_SESSION[$primacja]['SUM(stypendium1)'] + $_SESSION[$binacja]['SUM(stypendium2)'])."PLN</td></tr></table>";
            
            $suma_celebrans += $_SESSION[$primacja]['SUM(stypendium1)'] + $_SESSION[$binacja]['SUM(stypendium2)'];
               
            }
            echo "<br>Cumulus: ".$_SESSION["ile_ksiezy"]." x ".$suma_celebrans/$_SESSION["ile_ksiezy"]; 
          
} else if (isset($_GET["greg_termin"])==true ){
//------------------------------------------------------------------------------gregorianka

$_SESSION['greg_start']=strtotime($_GET["greg_termin"]);
$_SESSION['greg_end']=strtotime("+29 days", $_SESSION['greg_start']);
$greg_termin=$_GET["greg_termin"];
$ostatni_wpis =  mysqli_query($db_conn, 'SELECT * FROM gregorianka ORDER BY termin DESC LIMIT 1') ; 
$limit = mysqli_fetch_row($ostatni_wpis);   


        do {
            $sql= 'SELECT * FROM `gregorianka` WHERE termin BETWEEN "'.date("Y-m-d", $_SESSION['greg_start']).'" AND "'.date("Y-m-d", $_SESSION['greg_end']).'" AND (i1="" OR i2="" OR i3="" OR i4="")';
           $query_wpis = mysqli_query($db_conn, $sql);
		$_SESSION['gregoriana'] = $query_wpis->num_rows;
        $_SESSION['greg_start']=strtotime("+ 1 day",$_SESSION['greg_start']);
        $_SESSION['greg_end']=strtotime("+29 days", $_SESSION['greg_start']);
        if ($limit[0]==date("Y-m-d",$_SESSION['greg_start'])) {; break; }
    } while($_SESSION['gregoriana']<30);

        if ($limit[0]==date("Y-m-d",$_SESSION['greg_start'])){
            $_SESSION['greg_start']=strtotime("+ 1 day",$_SESSION['greg_start']);
            for($c=1; $c<=$_SESSION["ile_ksiezy"]; $c++){
            for($i=1; $i<=30; $i++){
                $termin_greg=date("Y-m-d",strtotime("+ ".$i." day",$_SESSION['greg_start']));
               $sql="INSERT INTO celebrans$c (termin, godzina1, intencja1, stypendium1, godzina2, intencja2, stypendium2) 
                VALUES('$termin_greg','','','','','','')"; 
                mysqli_query($db_conn, $sql);
				
            }}}
     
     echo "Najbliższy wolny termin na Msze Gregoriańskie:<br>".date("d.m", $_SESSION['greg_start'])." - ".date("d.m.Y", $_SESSION['greg_end']).
        "<br><form action='intencje.php' method='get'> 
        <input type='text' class='input' name='int_greg' placeholder='intencja'> 
        <input type='number' name='styp_greg'><input type='submit' value='zapisz'></form>";}
      
 elseif (isset($_GET["int_greg"])==true && isset($_GET["styp_greg"])==true) {
     $sql= 'SELECT * FROM `gregorianka` WHERE termin BETWEEN "'.date("Y-m-d", $_SESSION['greg_start']).'" AND "'.date("Y-m-d", $_SESSION['greg_end']).'" AND (i1="" OR i2="" OR i3="" OR i4="")';
           $query_wpis = mysqli_query($db_conn, $sql);
    $int_greg=$_GET["int_greg"];
    $styp_greg=$_GET["styp_greg"];
        echo "Msze Greogriańskie zapisane z powodzeniem!";
 
    while($gregoriana = mysqli_fetch_array($query_wpis)) {
   
        for($i=1; $i<=($_SESSION['ile_ksiezy']*2); $i++){
      $polei="i".$i;
      $poles="s".$i;
        $sql="UPDATE gregorianka SET  $polei = '$int_greg', $poles= '$styp_greg' WHERE termin='$gregoriana[termin]';";
      
       if ( $gregoriana[$polei]=="") {
       mysqli_query($db_conn, $sql );
       break; 
        }     
    }    
    }
} 
//----------------------------cito 
elseif (isset($_GET["cito_termin"])==true ){
    $_SESSION['cito_start']=strtotime($_GET["cito_termin"]);
    $cito_termin=$_GET["cito_termin"];
    $or="";
    for ($i=2; $i<$_SESSION["ile_ksiezy"]*2; $i++){
        $or.= "OR i$i=''";
    }
    $sql= 'SELECT * FROM `gregorianka` WHERE termin > "'.date("Y-m-d", $_SESSION['cito_start']).'" AND (i1="" '.$or.' )';
 //  echo $sql;
    $cito_wpis= mysqli_query($db_conn, $sql);
    $cito_row=mysqli_fetch_assoc($cito_wpis);
    $_SESSION['termin']=$cito_row["termin"];
    echo "<form action='intencje.php' method='POST'> 
    Najbliższy wolny termin Mszy św:<br>". $cito_row["termin"]."<br>
    <input type='submit' value='przejdź'></form>";


}
//-----------------------------kalendarz ----------------------------
   else if (isset($termin)==true){ 
    echo '<form method="post" id="formularz" action="kwerenda.php">
    <label for="termin">wybierz datę:</label> 
    <input type="button" onclick="dataMinus();" value="<">
    <input type="date" id="da" name="termin" onchange="this.form.submit()" value="'.$_SESSION["termin"].'">
    <input type="button" onclick="dataPlus();" value=">">
    </form><span align="center" style="color:red">'.$_SESSION["dni_tygodnia"][$day].'<br>'. @$_SESSION["nazwa_holy"].@$_SESSION["nazwa_zniesione"].'<br>'.@$_SESSION["odpust"].'</span>
    <form  method="post" action="funkcja.php">';;
    if(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["31"])) {
    echo '<br>DZISIAJ NIE SPRAWUJE SIĘ MSZY ŚWIĘTEJ';}
    elseif (date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["30"])||(date("d-m-Y", strtotime($termin))==date("d-m-Y",$daty["32"])))
    {  
        echo planszaone($termin);
        echo '<input type="submit" value="zapisz"> </form>';
    }
        else{
    echo plansza($termin);
    echo '<input type="submit" value="zapisz"> </form>
    <br><span  style="color:green" id="wynik" >'.@$_SESSION["wynik"].'</span>';
    }}
    
?>
<script>
   
    freeblock();
</script>


</div>


<div class="prawa"><u>Witaj, <?php echo $_SESSION["login"]; ?></u><br><br>
<a href="?intencja()">Intencje</a><br>
<a href="print.php">Wydrukuj</a><br>
<a href="javascript:stypendia()"> Podsumuj stypendia </a><br>
<a href="javascript:cito()">Najbliższy termin </a><br>
<a href="javascript:gregorianum()">Msze gregoriańskie </a><br>
<a href="javascript:ustawienia()">Ustawienia  </a><br>
<a style="color:red" href="logout.php">Wyloguj się!</a><br>
</div>


<footer>
<span style="text-align:center;">ks. Sebastian Kowalski&copy; 2022-<?php echo date("Y");?></span>
</footer>
</body>
</html>


