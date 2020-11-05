<?php
	$link=mysql_connect("localhost","root","");
	if(!$link){
		echo ("Errore nella connessione!");//si impalla ma almeno capisco che c'è un errore in connessione o selezione del DB
		exit();
	}
	$db_selected=mysql_select_db("my_dottore",$link);
	if(!$db_selected){
		echo ("Errore nella selezione del database!");//si impalla ma almeno capisco che c'è un errore in connessione o selezione del DB
		exit();
	}
	$ris=mysql_query("SELECT password FROM utente WHERE username='".$_POST["username"]."';") or die(mysql_error());
	$row=mysql_fetch_array($ris);
	if(!$row)//se lo facevo su $risultato non funzionava correttamente
		echo "false";
	else
		if($row["password"]!=$_POST["password"])
			echo "false";
		else
			echo "true";
?>