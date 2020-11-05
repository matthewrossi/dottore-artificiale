<?php
	$link=mysql_connect("localhost","root","");
	if(!$link){
		echo ("Errore nella connessione!");
		exit();
	}
	$db_selected=mysql_select_db("my_dottore",$link);
	if(!$db_selected){
		echo ("Errore nella selezione del database!");
		exit();
	}
        session_start();
        if(!isSet($_SESSION["user"]))  	
        	echo "Permesso negato!";
        else{
		mysql_query("UPDATE utente SET ".$_POST["name"]."='".$_POST["value"]."' WHERE id='".$_SESSION["user"]."';") or die(mysql_error());
	        echo $_POST["value"];
	}
?>