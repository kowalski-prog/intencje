<?php
session_start();


//--------------------------------------------bazadanych
$host = 'localhost';
$db_user ='komarkus_intencje';
$db_password = 'Intencje123**';
$db_name = 'komarkus_intencje';
$db_conn = mysqli_connect($host,$db_user,$db_password) 
or die ('Odpowiedź: Błąd połączenia z serwerem $host');
mysqli_select_db($db_conn, $db_name) or die('Trwa konserwacja bazy danych… Odśwież stronę za kilka sekund.');

function instal1() {  
    echo ' <form action="install.php" method="POST">
    <p>1. Baza danych</p>
        <table align="center"> 
        
     
        <TR><TD> <div class="divinsta"><span>login użytkownika:</span></div></TD>
        <TD><div class="divinsta"><input type="text" class="input" name="user" value="user"></div></TD></TR>
        <TR><TD><div class="divinsta"><span>hasło:</span></div> </TD>
        <TD><div class="divinsta"><input type="password" class="input" name="passwd" value=""></div></TD></TR>
        </TABLE>
        <br>
     <input type="submit" value="połącz">
    </form>';}
   
     $_SESSION["login"]= $_POST["user"];
     $login= $_POST["user"];
        $query_login = mysqli_query($db_conn, "SELECT * FROM konta WHERE name ='$login'");
		$ilu_userow = $query_login->num_rows;
		if($ilu_userow>0)
		{
  		 $record = mysqli_fetch_assoc($query_login);
  		 $hash = $record["pass"];
         $_SESSION['parafia']=$record['parafia'];
		}
  			 if (password_verify($_POST["passwd"], $hash))
			  {
				
             $_SESSION["strona"]=2;
             $_SESSION["zalogowan"]=true;
			} elseif (isset($_POST["passwd"])==true) echo "złe hasło lub login.";
	

     function instal2(){
        if  (isset($_POST["ile_ksiezy"])==true){
            $_SESSION["strona"]=3;
        }else if ($_SESSION["strona"]=2){
    echo '<form action= install.php method="POST" >
    <p>2. Liczba sprawowanych Mszy świętych:</p>
    <table align="center"> 
        <TR><TD> <div class="divinsta"><span>Ile księży celebruje Msze święte w Parafii?</span></div></TD>
        <TD><div class="divinsta"> <input type="number" class="inputa" name="ile_ksiezy" value="0"></div></TD></TR> 
        <TR><TD><div class="divinsta"><span>Ile sprawuje się Mszy świętych w Niedzielę? </span></div></TD>
        <TD><div class="divinsta"><input type="number" class="inputa" name="ile_niedziela" value="0"></div></TD></TR> 
        <TR><TD><div class="divinsta"><span>Ile sprawuje się Mszy świętych w dni powszednie?</span></div></TD>
        <TD><div class="divinsta"><input type="number" class="inputa" name="ile_mszy" value="0"></div></TD></TR> 
        <TR><TD><div class="divinsta"><span> Ile sprawuje się Mszy święych w Wigilię Bożego Narodzenia? </span></div></TD>
        <TD><div class="divinsta"><input type="number" class="inputa" name="ile_wigilia" value="0"></div></TD></TR> 
        <TR><TD><div class="divinsta"><span> Ile sprawuje się dodatkowych Mszy święych w święta zniesione? </span></div></TD>
        <TD><div class="divinsta"><input type="number" class="inputa" name="ile_znies" value="0"></div></TD></TR> 
        <TR><TD><div class="divinsta"><span> Ile Uroczystości Odpustowych obchodzi Parafia ?</span></div></TD> 
        <TD><div class="divinsta"> <input type="number" class="inputa" name="ile_odpustow" value="0"></div></TD></TR>
        </TABLE> <br>
   
     <input type="submit" value="dalej">
    </form>';}
    
   }
   

function instal3(){
    
    @$_SESSION["ile_ksiezy"]= $_POST["ile_ksiezy"];
    @$_SESSION["ile_niedziela"]= $_POST["ile_niedziela"];
    @$_SESSION["ile_mszy"]= $_POST["ile_mszy"];
    @$_SESSION["ile_wigilia"]= $_POST["ile_wigilia"];
    @$_SESSION["ile_znies"]= $_POST["ile_znies"];
    @$_SESSION["ile_odpustow"]= $_POST["ile_odpustow"];

    echo'<form action="install.php" method="POST" >     
    <p>3. Godziny Mszy świętych:</p>
     <span>porządek niedzielny:</span><br>';
    
    for($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
        echo'<input type="text" class="inputa" name="n_msza'.$i.'" placeholder="godzina Mszy '.$i.'"><br>';   
    } 
    echo' <br><span>porządek powszedni:</span><br>';
        for($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
            echo'<input type="text" class="inputa" name="pow_msza'.$i.'" placeholder="godzina Mszy '.$i.'"><br>';
        }
    echo' <br><span>godziny doadtkowych Mszy św. w święta zniesione:</span><br>';
        for($i=1; $i<=$_SESSION["ile_znies"]; $i++){
            echo'<input type="text" class="inputa" name="msza_znies'.$i.'" placeholder="godzina Mszy '.$i.'"><br>';
        }  
        echo '<br><span>Dni wolne celebransów:</span><br>';
        for($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){ 
        echo '<label for="wolne">Celebrans'.$i.'</label>
        <select name="wolne'.$i.'" id="wolne">
          <option value="Poniedziałek">Poniedziałek</option>
          <option value="Wtorek">Wtorek</option>
          <option value="Środa">Środa</option>
          <option value="Czwartek">Czwartek</option>
          <option value="Piątek">Piątek</option>
          <option value="Sobota">Sobota</option>
        </select><br>';}
     echo'<br><input type="submit" value="dalej">
    </form>';
   }


if (isset($_SESSION["pow_msza1"])!==true && isset($_POST["pow_msza1"])==true){
    for ($i=1; $i<=$_SESSION["ile_niedziela"]; $i++){
        $godzina_n="n_msza".$i;
        $_SESSION[$godzina_n]=  $_POST[$godzina_n];  
   }
    for ($i=1; $i<=$_SESSION["ile_mszy"]; $i++){
    $godzina_p="pow_msza".$i;
    $_SESSION[$godzina_p]=  $_POST[$godzina_p];  
    }
    for ($i=1; $i<=$_SESSION["ile_znies"]; $i++){
        $godzina_znies="msza_znies".$i;
        $_SESSION[$godzina_znies]=  $_POST[$godzina_znies];  
        }
    for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++){
        $wolne="wolne".$i;
        $_SESSION[$wolne]=  $_POST[$wolne];  
        }
        $_SESSION["strona"]=4;
    }
   
function instal4(){
echo ' <form action="libary.php" method="POST">
<p>4. SPECJALIA:</p>          
      <p> A. Roraty są:</p>
      <TABLE align="center">
      <TR><TD><div class="divinsta"><input type="radio" name="roraty" id ="roraty+" value="dod"> <label for="roraty+"> Mszą św. dodatkową , godz. </div></TD>
      <TD><div class="divinsta"><input type="text" class="inputa" name="g_roraty"></label></div></TD></TR>
      <TR><TD><div class="divinsta"><input type="radio" name="roraty" id ="roraty_godz" value="roraty_godz"> <label for="roraty_godz">  Mszą ranną o zmienionej godzinie , godz.</div></TD>
      <TD><div class="divinsta"><input type="text" class="inputa" name="roraty_msza"></label></div></TD></TR>
      <TR><TD><div class="divinsta"> <input type="radio" name="roraty" id ="roraty-" value="0"> <label for="roraty-"> Mszą wg stałego porządku Mszy św. </label></div></TR></TD>
      </TABLE><br>

<p>B. Czy w poniższe dni świąteczne opuszcza się pierwszą Mszę ranną? </p>  
<input type="checkbox" id="gwiazdka" name="gwiazdka" value="on"> <label for="gwiazdka">Boże Narodzenie (z racji Pasterki) </label><br>
<input type="checkbox" id="nowy_rok" name="nowy_rok" value="on"> <label for="nowy_rok"> Nowy Rok (Z racji nocnych zabaw)</label><br>
<input type="checkbox" id="wielkanoc" name="wielkanoc" value="on"> <label for="wielkanoc">Wielkanoc (z racji Liturgii Wigilii Paschalnej)</label><br><br>
   
<p>C. Szczególne celebracje: </p>  
<span> godziny Mszy w Wigilię Bożego Narodzenia:</span><br>';

for($i=1; $i<=$_SESSION["ile_wigilia"]; $i++){
    echo '<input type="text" class="inputa" name="wigilia'.$i.'" placeholder="Msza '.$i.'"><br>';
}
echo'<br><span> godzina celebracji Wigilii Paschalnej:</span><br><br>
<input type="text" class="inputa" name="pascha" placeholder="Wigilia Paschalna"><br>

<br><p> Odpust Parafialny</p>';
for ($i=1; $i<=$_SESSION["ile_odpustow"]; $i++){
    
echo '<span><b> Odpust '.$i.'.</b><br><u> Data:</u></span> <br>
<input type="radio" name="odpust_ruch'.$i.'"  value="n_ruch" id="n_ruch'.$i.'"> <label for="ruch">święta stałe:   <br> <input type="text" class="inputa" name="odpustd'.$i.'" placeholder="DD-MM"><br>
<input type="radio" name="odpust_ruch'.$i.'"  value="ruch" id="ruch'.$i.'"> święta ruchome:<br> <label for="ruch"> 
        <select name="ruchome'.$i.'" id="ruchome" placeholder="święta ruchome">
         <option value="ruchome" selected disabled>święta ruchome:</option>
          <option value="Uroczystość Zmartwychwstania Pańskiego">Uroczystość Zmartwychwstania Pańskiego</option>
          <option value="Uroczystość Wniebowstąpienia Pańskiego">Uroczystość Wniebowstąpienia Pańskiego</option>
          <option value="Uroczystość Zesłania Ducha Świętego">Uroczystość Zesłania Ducha Świętego</option>
          <option value="Uroczystość Trójcy Przenajświętszej">Uroczystość Trójcy Przenajświętszej</option>
          <option value="Uroczystość Bożego Ciała">Uroczystość Bożego Ciała</option>
          <option value="Uroczystość Chrystusa, Króla Wszechświata">Uroczystość Chrystusa, Króla Wszechświata</option>
        </select><br><br>
<span><u> tytuł odpustu: </u></span><br>
<input type="text" class="inputa" name="tytul'.$i.'" placeholder="np. św. Jana Apostoła" width="400"><br><br>
<span> <u>Uroczystość odpustowa jest obchodzona: </u> </span><br>
<div class="divek" align="center">
<input type="radio" name="odpust'.$i.'" id ="ipsadie" value="ipsadie"> <label for="ipsadie"> Ipsa Die </label><br>
<input type="radio" name="odpust'.$i.'" id ="niedz-" value="niedz-"> <label for="niedz-">  Niedziela przed Ipsa Die </label><br>
<input type="radio" name="odpust'.$i.'" id ="niedz+" value="niedz+"> <label for="niedz+"> Niedziela po Ipsa Die </label><br><br>
</div>
<u>Porządek Mszy św. w Uroczystość Odpustową: </u><br> <select name="porz_odp'.$i.'" >
<option value="niedzielny">Niedzielny</option>
<option value="zniesiony">Jak w święta zniesione </option> 
<option value="zwykły">Zwykły</option>
</select></label><br><br>';}

echo'<p>D. Sezon:</p>
<input type="checkbox" id="koledy" name="koledy"> <label for="koledy"> Czy w czasie kolędowym sprawuje się Mszę św. wieczorną w dni powszednie? </label><br>
<input type="checkbox" id="lato-zima" name="lato-zima"> <label for="lato-zima"> Czy w zależności od pory roku zmieniają się godziny Mszy św ?</label><br><br>
<table border=0 align="center"> <TR><TD><span>Początek sezonu letniego:</span></TD><TD><span><input type="text" class="inputa" name="zm_lato" placeholder="DD-MM"> </TD></TR>
<TR><TD><span>Początek sezonu zimowego</span></TD><TD><input type="text" class="inputa" name="zm_zima" placeholder="DD-MM"> </TD></TR>  
<TR><TD><span>zmiana godziny Mszy św. wieczornej na:</span></TD><TD> <span><input type="text" class="inputa" name="zm_godz" placeholder="00.00"></TD><TR></TABLE><br>
<input type="submit" value="instaluj księgę">
</form>';}


function instal5(){
    echo '<p>Konta edytorskie księgi:</p><br> <form action="rejestr.php" method="POST"><TABLE align="center" border="0">
        <TR><TD><span> login: </span></TD><TD><input type="text" class="inputa" name="login0" value="biuro"></TD></TR>
       <TR><TD><span> hasło: </span></TD><TD><input type="password" class="inputa" name="pass0"></TD></TR><TR><TD>';
       for ($i=1; $i<=$_SESSION["ile_ksiezy"]; $i++) {
        echo '<TR><TD><span> login: </span></TD><TD><input type="text" class="inputa" name="login'.$i.'" value="celebrans'.$i.'"></TD></TR>
        <TR><TD> <span>hasło:</span></TD><TD><input type="password" class="inputa" name="pass'.$i.'"></TD></TR><TR><TD>';
       }
       echo '</table><br><input type="submit" value="zapisz"></form>';
}
function instal6(){
    echo "Instalaca przebiegła pomyślnie.<br><br> Dziękujemy za wybór naszego programu<br><br>
    przejdź do <a href='intencje.php'>Księgi Intencji</a> ";
}
?>

<html>
<head>
<meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSTALACJA</title>
<link rel="stylesheet" href="styl.css">

</head>
<body>


<div class="naglowek"><h1>INSTALACJA</h1><h2 style="text-align:center; color:aliceblue">Księga Intencji</h2></div>

<div class="grid-container">
  <div class="lewa" id="lewy">


<?php 

if  (isset($_SESSION["zalogowan"])!=true) instal1();
if ($_SESSION["strona"]==2) instal2();  
if ($_SESSION["strona"]==3) instal3();   
if ($_SESSION["strona"]==4) instal4(); 
if ($_SESSION["strona"]==5) instal5(); 
if ($_SESSION["strona"]==6) instal6();
 
?>

</div>


<div class="prawa"><u>Witaj, użytkowniku <?php echo $_SESSION["login"]; ?></u><br>
<a style="color:red" href="logout.php">Wyczyść się!</a><br>
</div>


<footer>
<div><span style="text-align:center;">ks. Sebastian Kowalski&copy; 2022-<?php echo date("Y");?></span></div>
</footer>
</body>
</html>