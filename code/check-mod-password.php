<?php
	$link=mysql_connect("localhost","root","");
	if(!$link){
		echo ("Errore nella connessione!");//si impalla ma almeno capisco che c'� un errore in connessione o selezione del DB
		exit();
	}
	$db_selected=mysql_select_db("my_dottore",$link);
	if(!$db_selected){
		echo ("Errore nella selezione del database!");//si impalla ma almeno capisco che c'� un errore in connessione o selezione del DB
		exit();
	}
	session_start();
	if(!isSet($_SESSION["user"])&&!isSet($_POST["value"]))
		echo "Permesso negato";
	else{
		$ris=mysql_query("SELECT password FROM utente WHERE id='".$_SESSION["user"]."';") or die(mysql_error());
		$row=mysql_fetch_array($ris);
		if(!$row)
			echo "false";
		else
			if($row["password"]!=$_POST["old_pswd"])
				echo "false";
			else
				echo "true";
	}
?>