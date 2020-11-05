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
	if(isSet($_POST["cf"])){
		$ris=mysql_query("
			SELECT id
			FROM paziente
			WHERE cf='".$_POST["cf"]."' AND id_utente='".$_SESSION["user"]."'
		;") or die("alert(".mysql_error().");");
		if(!mysql_fetch_array($ris)){
			mysql_query("INSERT INTO paziente VALUES (NULL,'".$_POST["cf"]."','".$_POST["nome"]."','".$_POST["cognome"]."','".$_POST["data_nascita"]."','".$_POST["luogo_nascita"]."','".$_POST["indirizzo"]."','".$_POST["provincia"]."',".$_SESSION["user"].");") or die("alert(".mysql_error().");");
			$data=explode('/',$_POST["data_nascita"]);
			$ris=mysql_query("
				SELECT COUNT(*) as N
				FROM paziente
				WHERE id_utente='".$_SESSION["user"]."';
			") or die("alert(".mysql_error().");");
			$row=mysql_fetch_array($ris);
			$row["N"]--;
			echo "
				//Adds a new row to the data table, and returns the index of the new row.
				data.addRow(['".$_POST["cf"]."', '".$_POST["nome"]."', '".$_POST["cognome"]."', new Date (".$data[0].",".$data[1]."-1,".$data[2]."), '".$_POST["luogo_nascita"]."', '".$_POST["indirizzo"]."', '".$_POST["provincia"]."', '<img id=\'mod\' src=./images/edit.png onclick=set_dialog(this,\'".$_POST["cf"]."\',\'".$row["N"]."\') /><img src=./images/drop.png onclick=del_paziente(\'".$row["N"]."\') />']);
				dashboard.bind(stringFilter, table).draw(data);	//Non so se serve la bind
			";			
		}
		else 
			echo "alert('Hai già in cura questo paziente!')";
	}
	else
		echo "alert('Errore: inserimento non avvenuto!')";	
?>