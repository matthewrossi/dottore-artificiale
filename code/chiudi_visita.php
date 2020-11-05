<?php
	$link=mysql_connect("localhost","root","");
	if(!$link){
		echo "alert('Errore nella connessione!')";
		exit();
	}
	$db_selected=mysql_select_db("my_dottore",$link);
	if(!$db_selected){
		echo "alert('Errore nella selezione del database!')";
		exit();
	}
	session_start();
	if(isSet($_SESSION["visita"])){
		mysql_query("DROP TABLE m".$_SESSION["visita"].";") or die(mysql_error());
		unset($_SESSION["visita"], $_SESSION["sintomo"], $_SESSION["paziente"]);
	}
?>