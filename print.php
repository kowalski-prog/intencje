<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wydruk intencji</title>
    <script type="text/javascript">
	function PrintPage() {
		window.print();
	}
    </script>
    <style>	
		table {
			
			
            font-size: 13;
		}	
        td {
            width: auto;
        }
        .maks{
            width: 100%;
        }
    
        div {
            margin: 20px;
            width: 595px;
        }
 
		@media print{
			.print {
				display:none;
			}
		}
		
		
	</style>
</head>
<body>
<div class="print">
<form action="print.php" method="get">
<input type="date" name="pocz" onchange="this.form.submit()" value="<?php echo @$_GET['pocz']; ?>">
    <input type="date" name="koniec" onchange="this.form.submit()" value="<?php echo @$_GET['koniec']; ?>">
    <button  onclick="PrintPage()">Drukuj</button>
</form><a href="intencje.php"><button onclick="intencje.php">Wyjdź</button></a>

</div>

<?php
session_start();
require_once "connect.php";


if (!isset($_SESSION['termin'])) {
$_SESSION['termin']=date("Y-m-d");}
$dni_tygodnia = array( 'Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota' );
$miesiace= array('nic','stycz.','luty',  'mar.', 'kwie.','maj','czer.','lip.','sierp.','wrze.','paź.','list.','gru.');
$dzisiaj=strtotime("last monday");
$tydzien=strtotime("+6 days", $dzisiaj);
$polaczenie = mysqli_connect($host, $db_user, $db_password, $db_name);
if (!isset($_SESSION['zalogowany']))
{
    header('location: log.php');
    exit();
}
else{
    if((isset($_GET['pocz'])==true) && (isset($_GET['koniec'])==true)){
        $termin1=$_GET['pocz']; 
        $termin2=$_GET['koniec']; } else 
     {$termin1=date("Y-m-d", $dzisiaj); 
    $termin2=date("Y-m-d", $tydzien);
    $data_poczatku=date_create($termin1);
    $data_konca=date_create($termin2);

    }

    $x= $y= "";

    for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
       $x.=" celebrans".$i.".intencja1,";
    }
    for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
       $x.=" celebrans".$i.".intencja2,";
    }
    for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
        $x.=" celebrans".$i.".godzina1,";
     }
     for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
        if ($i< $_SESSION["ile_ksiezy"]) {$coma=",";} else {$coma="";}
        $x.=" celebrans".$i.".godzina2".$coma;
     }
    for($i=2; $i<=$_SESSION["ile_ksiezy"]; $i++){
       $y.="INNER JOIN celebrans".$i." ON celebrans".$i.".termin = celebrans1.termin ";
    }

   $sql="SELECT celebrans1.termin, $x
   FROM celebrans1 $y where celebrans1.termin between'$termin1' and '$termin2';";

    $query_records = mysqli_query($db_conn, "$sql");
    $liczba_kolumn=mysqli_field_count($db_conn);
    $ile_terminow = mysqli_num_rows($query_records);
    $godziny_mszy= ($_SESSION["ile_ksiezy"]*2)+1;
 
 if (isset($_GET['pocz'])==true) 
$data_poczatku= date_create(@$_GET['pocz']);
 if ( isset($_GET['koniec'])==true) 
$data_konca=date_create(@$_GET['koniec']);
 
    if($ile_terminow>0)
    {   echo "<div><h1 align='center'>Intencje Mszalne</h1> <p align='center'>". date_format(@$data_poczatku,'d.m')."-".date_format(@$data_konca,'d.m.Y')."</p>
        <table border=1>
        <tr>
            <th>data</th>
            <th>godzina</th>
            <th class='maks'>intencja</th>
        </tr>";
        $i=0;
     
        while($row = mysqli_fetch_array($query_records, MYSQLI_NUM)) {
            $licznik=1;
            $wyciag=array_slice($row,$godziny_mszy);
            $godz = array_unique($wyciag);
            if (array_search("",$godz)>0) unset($godz[array_search("",$godz)]);
            
            $date=date_create($row[0]);
            $datarr = date_format($date, "w" );
            $miesiac = date_format($date, "n");
            
            $wspolczynnik=($_SESSION["ile_ksiezy"]*2)/count($godz);
            $wspolczynnik2=($_SESSION["ile_ksiezy"]*2)/count($godz);

            $daty=array_column($_SESSION["holidays"], 0);
            $nazwa_holy=array_column($_SESSION["holidays"],1);
            $_SESSION["nazwa_holy"] ="";
                 for($i=0;$i<=(count($_SESSION["holidays"])-1);$i++){
                    if(date("d-m-Y", strtotime($row[0]))==date("d-m-Y",$daty[$i])){
                    $_SESSION["nazwa_holy"] = $nazwa_holy[$i];
                    break;
                    }}
          if($dni_tygodnia[ $datarr ]!=="Niedziela"){
            echo "<tr><th align='center' rowspan=".count($godz).">".$dni_tygodnia[ $datarr ]."<br>"
            .date_format($date,"d")." ".$miesiace[$miesiac]."<br> 
            <span name='".$row[0]."' id='holy'".$i.">".$_SESSION['nazwa_holy']."</span></td>";      
                foreach($godz as $g){
                    echo "<TD align='center'>$g</TD><TD>";
             
                    $l_int = 1;
                    
                    for ($a=0; $a<$wspolczynnik; $a++){
                        $pole="";
                        if ($row[$licznik]=="" || $row[$licznik]==null || $row[$licznik]=="free"){
                            $pole.="";
                            $licznik++;
                           // $l_int-=1;
                           
                        }
                        else{
                            $pole.= "$l_int) ".$row[$licznik]."<br>";
                            $licznik++;
                            $l_int++; 
                            echo $pole;
                        }
                    }
                    echo "</TD></tr>";
                }     
        } 
        if ($dni_tygodnia[ $datarr ]=="Niedziela"){
            $licznik=1;
            
            echo "<tr><th align='center' rowspan=".count($godz).">".$dni_tygodnia[ $datarr ]."<br>"
            .date_format($date,"d")." ".$miesiace[$miesiac]."<br> 
            <span name='".$row[0]."' id='holy'".$i.">".$_SESSION['nazwa_holy']."</span></td>";      
                foreach($godz as $g){
                    echo "<TD align='center'>$g</TD><TD>";
                    for ($a=1; $a<$wspolczynnik2; $a++){
                        if ($wspolczynnik2>=2){
                       echo "$a) ".$row[$licznik]."<br>";
                    }
                        else {
                         echo $row[$licznik]."<br>";    
                        }
                    }
                    echo "</TD></tr>";
                    $licznik++;
                }     
        }
        $i++;
        }
     echo "</table></div>";
    
        
    } else {
        echo "dobierz drugą datę.";
  
       
    }
}
    ?>



   
</body>
</html>