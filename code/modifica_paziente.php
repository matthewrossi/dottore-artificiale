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
		if(isSet($_SESSION["user"])){
			if($_POST["cf"]!=$_POST["cf_vecchio"])
				$ris=mysql_query("SELECT P.id FROM paziente WHERE id_utente='".$_SESSION["user"]."' AND cf='".$_POST["cf"]."';");
			if(!mysql_fetch_array($ris)){
				mysql_query("
					UPDATE paziente SET 
						cf='".$_POST["cf"]."',
						nome='".$_POST["nome"]."',
						cognome='".$_POST["cognome"]."',
						data_nascita='".$_POST["data_nascita"]."',
						luogo_nascita='".$_POST["luogo_nascita"]."',
						indirizzo='".$_POST["indirizzo"]."',
						provincia='".$_POST["provincia"]."'
					WHERE cf='".$_POST["cf_vecchio"]."' AND id_utente='".$_SESSION["user"]."';
				") or die("alert(".mysql_error().");");
				$data=explode('/',$_POST["data_nascita"]);
				echo "
					data.setCell(row,0,'".$_POST["cf"]."');
					data.setCell(row,1,'".$_POST["nome"]."');
					data.setCell(row,2,'".$_POST["cognome"]."');
					data.setCell(row,3,new Date (".$data[0].",".$data[1]."-1,".$data[2]."));
					data.setCell(row,4,'".$_POST["luogo_nascita"]."');
					data.setCell(row,5,'".$_POST["indirizzo"]."');
					data.setCell(row,6,'".$_POST["provincia"]."');
					data.setCell(row,7,'<img id=\'mod\' src=./images/edit.png onclick=set_dialog(this,\'".$_POST["cf"]."\','+row+') /><img src=./images/drop.png onclick=del_paziente('+row+') />');
					dashboard.bind(stringFilter, table).draw(data);
				";
			}
			else
				echo "alert('Vi � gi� un paziente con quel codice fiscale!');";	
		}
		else
			echo "Permesso negato";
	}
	else
		echo "alert('Errore: modifica non avvenuta!');";
?>