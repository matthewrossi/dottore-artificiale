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
		$id_utente=$_SESSION["user"];
		$ris=mysql_query("SELECT id FROM paziente WHERE cf='".$_POST["cf"]."' AND id_utente='".$id_utente."';") or die("alert(".mysql_error().");");
		$row=mysql_fetch_array($ris);
		$id_paziente=$row["id"];
		$ris_out=mysql_query("SELECT id,data FROM visita WHERE id_paziente='".$id_paziente."';") or die("alert(".mysql_error().");");
		while($row=mysql_fetch_array($ris_out)){	
			$id_visita=$row["id"];
			$data=$row["data"];
			$ris_in=mysql_query("SELECT id_sintomo FROM presenta WHERE id_visita='".$id_visita."';") or die("alert(".mysql_error().");");
			while($row=mysql_fetch_array($ris_in)){
				$id_sintomo=$row["id_sintomo"];
				$ris=mysql_query("DELETE FROM presenta WHERE id_visita='".$id_visita."' AND id_sintomo='".$id_sintomo."';") or die("alert(".mysql_error().");");
				if(!$ris)
					echo "alert('Il sintomo n° ".$id_sintomo." della vista del ".$data." non è stata eliminato!');";
			}
			$ris=mysql_query("DELETE FROM visita WHERE id='".$id_visita."';") or die("alert(".mysql_error().");");
			if(!$ris)
				echo "alert('La vista del ".$data." non è stata eliminata!');";
		}
		mysql_query("DELETE FROM paziente WHERE id='".$id_paziente."';") or die("alert(".mysql_error().");");
		$ris=mysql_query("SELECT COUNT(*) as N FROM paziente WHERE id_utente='".$id_utente."';");
		$row=mysql_fetch_array($ris);
		echo "
			data.removeRow(row);
			for(var i=row;i<".$row["N"].";i++)
				data.setCell(i,7,'<img id=\'mod\' src=./images/edit.png onclick=set_dialog(this,\''+data.getFormattedValue(i, 0)+'\','+i+') /><img src=./images/drop.png onclick=del_paziente(\''+data.getFormattedValue(i, 0)+'\','+i+') />');
			dashboard.bind(stringFilter, table).draw(data);	//Non so se serve la bind
		";
	}
	else
		echo "alert('Il paziente non è stato eliminato')";
?>	