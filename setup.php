<?php
session_start();
if (isset($_POST["s_pass"])==true && isset($_POST["n_pass"])==true) zmiana_hasla();
if (isset($_POST["pow_msza1"])==true) godziny();
if (isset($_POST["gwiazdka"])==true) specjalia();
function zmiana_hasla(){
    require "connect.php";
    $sql='SELECT * FROM `users` WHERE `user_id`= "'.$_SESSION['id'].'"';
    $query= mysqli_query($db_conn, $sql);
    $record= mysqli_fetch_array($query);
    $hash= $record["user_passwordhash"];
    if (password_verify($_POST["s_pass"], $hash)){
       $user_password_hash = password_hash($_POST["n_pass"], PASSWORD_DEFAULT);
       $sql2= "UPDATE `users` SET `user_passwordhash`='".$user_password_hash."' WHERE user_id='".$_SESSION['id']."'";
      if (mysqli_query($db_conn, $sql2)) ;
    }}
function godziny(){
    require "connect.php";
    $formula1= $formula2= $formula3= $formula4="";
    for ($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
        $msza="pow_msza".$i;
        $formula1.= $msza." = ".$_POST[$msza].", ";
    }
    for ($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
        $msza="n_msza".$i;
        $formula2.= $msza." = ".$_POST[$msza].", ";
    }
    for ($i=1; $i<=$_SESSION["ile_znies"]; $i++){
        $msza="msza_znies".$i;
        $formula3.= $msza." = ".$_POST[$msza].", ";
    }
    for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
        $wolne="wolne".$i;
        if ($i==$_SESSION["ile_ksiezy"]) { $formula4.= $wolne." = '".$_POST[$wolne]."'";}
        else{
        $formula4.= $wolne." = '".$_POST[$wolne]."', ";}
    }
    
    $sql='UPDATE ustawienia SET '.$formula1.$formula2.$formula3.$formula4;
    $query= mysqli_query($db_conn, $sql); 
}    
function specjalia(){
    require "connect.php";
if (isset($_POST["gwiazdka"])==true && $_POST["gwiazdka"]=="on") $gwiazdka = 1; else $gwiazdka = 0 ;
if (isset($_POST["nowy_rok"])==true && $_POST["nowy_rok"]=="on") $nowy_rok = 1; else $nowy_rok = 0 ;
if (isset($_POST["wielkanoc"])==true && $_POST["wielkanoc"]=="on") $wielkanoc = 1; else $wielkanoc = 0 ;
if (isset($_POST["koledy"])==true && $_POST["koledy"]=="on") $koledy = 1; else $koledy = 0 ;
if (isset($_POST["lato-zima"])==true && $_POST["lato-zima"]=="on") $lato_zima = 1; else $lato_zima = 0 ;
if (isset($_POST["g_roraty"])==true && $_POST["g_roraty"]!=="") $g_roaty =$_POST["g_roraty"]; else $g_roraty=""; 
if (isset($_POST["roraty_godz"])==true && $_POST["g_roraty"]!=="") $g_roaty =$_POST["g_roraty"]; else $g_roraty="";  
$sezon = $koledy.$lato_zima;
$prime_holy = $gwiazdka.$nowy_rok.$wielkanoc;

$msze_niedzielna= $porzadek= $msze_powszednia=$msze_wigilijne= $msze_znies=$odpusty= $wolni= $tytuly= $ipsadiem="";
    for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
        $wigilia= "wigilia".$i;
        $msze_wigilijne .="wig_msza".$i."= '".$_POST["wigilia$i"]."', ";
    }/////////
    for($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
        if ($_POST["odpust_ruch$i"]=="ruch") 
        {$odpusty .="odpust".$i."= '".$_POST["ruch$i"]."',"; 
            $_POST["tytul$i"]=$_POST["ruch$i"];
        } 
            else {
                $odpusty .=" odpust".$i."= '".$_POST["odpustd$i"]."', ";}

        $ipsadiem .=" ipsadie".$i."= '".$_POST["odpust$i"]."', "; 
        $tytuly .=  " tytul".$i."= '".$_POST["tytul$i"]."', ";
        $porzadek .= " porz_odp".$i."= '".$_POST["porz_odp$i"]."', ";
    }
    $sql= "UPDATE ustawienia SET". $odpusty.$porzadek." prime_holy='".$prime_holy."', sezon= '".$sezon."', 
    zm_lato='".$_POST["lato"]."', zm_zima='".$_POST["zima"]."', zm_godz='".$_POST["zm_godz"]."', ".$ipsadiem." 
    ".$tytuly." roraty= '".$_POST["roraty"]."', godz_roraty='".$_POST["g_roraty"]."',". $msze_wigilijne."pascha='".$_POST["pascha"]."'";
    mysqli_query($db_conn, $sql);
}
    
function instal3(){
    echo'<form action="setup.php" method="POST" >     
    <p>3. Godziny Mszy świętych:</p>
     <span>porządek niedzielny:</span><br>';
    
    for($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
        echo'<input type="text" class="inputa" name="n_msza'.$i.'"  value="'.$_SESSION["n_msza$i"].'"><br>';   
    } 
    echo' <br><span>porządek powszedni:</span><br>';
        for($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
            echo'<input type="text" class="inputa" name="pow_msza'.$i.'" value="'.$_SESSION["pow_msza$i"].'" ><br>';
        }
    echo' <br><span>godziny doadtkowych Mszy św. w święta zniesione:</span><br>';
        for($i=1; $i<=$_SESSION["ile_znies"]; $i++){
            echo'<input type="text" class="inputa" name="msza_znies'.$i.'" value="'.$_SESSION["msza_znies$i"].'"><br>';
        }  
        echo '<br><span>Dni wolne celebransów:</span><br>';
        for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){ 
        echo '<label for="wolne">Celebrans'.$i.'</label>
        <select name="wolne'.$i.'" id="wolne" placeholder>
          <option value="'.$_SESSION["wolne$i"].'">'.$_SESSION["wolne$i"].'</option>
          <option value="Poniedziałek">Poniedziałek</option>
          <option value="Wtorek">Wtorek</option>
          <option value="Środa">Środa</option>
          <option value="Czwartek">Czwartek</option>
          <option value="Piątek">Piątek</option>
          <option value="Sobota">Sobota</option>
        </select><br>';}
     echo'<br><input type="submit" value="zmień">
    </form>';
   }

   
function instal4(){
$v1 = $v2= $v3= $t1= $t2= "";
    if ($_SESSION["roraty"]=="dod"){
        $v1 = "checked";
        $t1 = $_SESSION["godz_roraty"];
    }elseif ($_SESSION["roraty"]=="roraty_msza"){
        $v2= "checked";
        $t2=$_SESSION["godz_roraty"];
    } else {
        $v3="checked";
    }
echo ' <form action="setup.php" method="POST">
<p>4. SPECJALIA:</p>          
      <p> A. Roraty są:</p>
      <TABLE align="center">
      <TR><TD><div class="divinsta"><input type="radio" name="roraty" id ="roraty+" value="dod" '.$v1.'> <label for="roraty+"> Mszą św. dodatkową , godz. </div></TD>
      <TD><div class="divinsta"><input type="text" class="inputa" name="g_roraty" value="'.$t1.'"></label></div></TD></TR>
      <TR><TD><div class="divinsta"><input type="radio" name="roraty" id ="roraty_godz" value="roraty_godz '.$v2.' <label for="roraty_godz">  Mszą ranną o zmienionej godzinie , godz.</div></TD>
      <TD><div class="divinsta"><input type="text" class="inputa" name="roraty_msza" value="'.$t2.'" ></label></div></TD></TR>
      <TR><TD><div class="divinsta"> <input type="radio" name="roraty" id ="roraty-" value="0" '.$v3.'> <label for="roraty-"> Mszą wg stałego porządku Mszy św. </label></div></TR></TD>
      </TABLE><br>';
    if ($_SESSION["popasterce"]==1) $a1= "checked";
   if ($_SESSION["posylwestrze"]==1)$a2= "checked";
   if ($_SESSION["popaschalnej"]==1) $a3= "checked";
   if ($_SESSION["sezon_koledowy"]==1) $a4= "checked";
   if ($_SESSION["lato_zima"]==1) $a5= "checked";
   

echo '<p>B. Czy w poniższe dni świąteczne opuszcza się pierwszą Mszę ranną? </p>  
<input type="checkbox" id="gwiazdka" name="gwiazdka" value="on" '.$a1.'> <label for="gwiazdka">Boże Narodzenie (z racji Pasterki) </label><br>
<input type="checkbox" id="nowy_rok" name="nowy_rok" value="on" '.$a2.'> <label for="nowy_rok"> Nowy Rok (Z racji nocnych zabaw)</label><br>
<input type="checkbox" id="wielkanoc" name="wielkanoc" value="on" '.$a3.'> <label for="wielkanoc">Wielkanoc (z racji Liturgii Wigilii Paschalnej)</label><br><br>
   
<p>C. Szczególne celebracje: </p>  
<span> godziny Mszy w Wigilię Bożego Narodzenia:</span><br>';

for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
    echo '<input type="text" class="inputa" name="wigilia'.$i.'" value="'.$_SESSION["wig_msza$i"].'"><br>';
}
echo'<br><span> godzina celebracji Wigilii Paschalnej:</span><br><br>
<input type="text" class="inputa" name="pascha" value="'.$_SESSION["pascha"].'"><br>

<br><p> Odpust Parafialny</p>';
$odp1= $odp2="";
for ($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
    if (strlen($_SESSION["odpust$i"]<=5)) {$odp1="checked"; $odpust=$_SESSION["odpust$i"];}
    if (strlen($_SESSION["odpust$i"]>5)) {$odp2="checked"; $odpust="";}
echo '<span><b> Odpust '.$i.'.</b><br><u> Data:</u></span> <br>
<input type="radio" name="odpust_ruch'.$i.'"  value="n_ruch" id="n_ruch'.$i.'" '.$odp1.'> <label for="ruch">święta stałe:   <br> <input type="text" class="inputa" name="odpustd'.$i.'" placeholder="DD-MM" value="'.@$odpust.'"><br>
<input type="radio" name="odpust_ruch'.$i.'"  value="ruch" id="ruch'.$i.'" '.$odp2.'> święta ruchome:<br> <label for="ruch"> 
        <select name="ruch'.$i.'" id="ruchome" placeholder="święta ruchome">';
        if (strlen($_SESSION["odpust$i"]<=5))  echo '<option value="ruchome" selected disabled>święta ruchome:</option>';
        if (strlen($_SESSION["odpust$i"]>5)) echo '<option value="'.$_SESSION["odpust$i"].'">'.$_SESSION["odpust$i"].'</option>';
          echo '<option value="Uroczystość Zmartwychwstania Pańskiego">Uroczystość Zmartwychwstania Pańskiego</option>
          <option value="Uroczystość Wniebowstąpienia Pańskiego">Uroczystość Wniebowstąpienia Pańskiego</option>
          <option value="Uroczystość Zesłania Ducha Świętego">Uroczystość Zesłania Ducha Świętego</option>
          <option value="Uroczystość Trójcy Przenajświętszej">Uroczystość Trójcy Przenajświętszej</option>
          <option value="Uroczystość Bożego Ciała">Uroczystość Bożego Ciała</option>
          <option value="Uroczystość Chrystusa, Króla Wszechświata">Uroczystość Chrystusa, Króla Wszechświata</option>
        </select><br><br>
<span><u> tytuł odpustu: </u></span><br>
<input type="text" class="inputa" name="tytul'.$i.'" placeholder="np. św. Jana Apostoła" width="400" value="'.@$_SESSION["tytul$i"].'"><br><br>
<span> <u>Uroczystość odpustowa jest obchodzona: </u> </span><br>';
if ($_SESSION["ipsadie$i"]=="ipsadie") $ips1="checked";
if ($_SESSION["ipsadie$i"]=="niedz-") $ips2="checked";
if ($_SESSION["ipsadie$i"]=="niedz+") $ips3="checked";
echo '<input type="radio" name="odpust'.$i.'" id ="ipsadie" value="ipsadie" '.@$ips1.'> <label for="ipsadie"> Ipsa Die </label><br>
<input type="radio" name="odpust'.$i.'" id ="niedz-" value="niedz-" '.@$ips2.'> <label for="niedz-">  Niedziela przed Ipsa Die </label><br>
<input type="radio" name="odpust'.$i.'" id ="niedz+" value="niedz+" '.@$ips3.'> <label for="niedz+"> Niedziela po Ipsa Die </label><br><br>';

echo '<u>Porządek Mszy św. w Uroczystość Odpustową: </u><br> <select name="porz_odp'.$i.'" >
<option value="'.$_SESSION["porz_odp$i"].'">'.$_SESSION["porz_odp$i"].'</option>
<option value="niedzielny">Niedzielny</option>
<option value="zniesiony">Jak w święta zniesione </option> 
<option value="zwykły">Zwykły</option>
</select></label><br><br>';}

echo'<p>D. Sezon:</p>
<input type="checkbox" id="koledy" name="koledy" '.$a4.'> <label for="koledy"> Czy w czasie kolędowym sprawuje się Mszę św. wieczorną w dni powszednie? </label><br>
<input type="checkbox" id="lato-zima" name="lato-zima" '.$a5.'> <label for="lato-zima"> Czy w zależności od pory roku zmieniają się godziny Mszy św ?</label><br><br>
<table border=0 align="center"> <TR><TD><span>Początek sezonu letniego:</span></TD><TD><span><input type="text" class="inputa" name="lato" placeholder="DD-MM" value="'.$_SESSION["zm_lato"].'"> </TD></TR>
<TR><TD><span>Początek sezonu zimowego</span></TD><TD><input type="text" class="inputa" name="zima" placeholder="DD-MM" value="'.$_SESSION["zm_zima"].'"> </TD></TR>  
<TR><TD><span>zmiana godziny Mszy św. wieczornej na:</span></TD><TD> <span><input type="text" class="inputa" name="zm_godz" placeholder="00.00" value="'.$_SESSION["zm_godz"].'"></TD><TR></TABLE><br>
<input type="submit" value="zmień">
</form>';}


function instal5(){

    echo '<p>Zmiana hasła:</p><br> <form action="setup.php" method="POST"><TABLE align="center" border="0">
        <TR><TD><span> login: </span></TD><TD><span>'.$_SESSION["login"].'</span></TD></TR>
       <TR><TD><span> stare hasło: </span></TD><TD><input type="password" class="inputa" name="s_pass"></TD></TR>
       <TR><TD><span> nowe hasło: </span></TD><TD><input type="password" class="inputa" name="n_pass"></TD></TR>
       </table><br><input type="submit" value="zmień"></form>';
}

?>



<html>
<head>
<link rel="stylesheet" href="styl.css">
</head>
<body>

  <div class="lewa" id="lewy">

<?php 
 
 instal3();   
 instal4();
instal5(); 
?>

</div>

</body>
</html>