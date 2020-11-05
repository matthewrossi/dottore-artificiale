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
	if(!isSet($_POST["username"]))
		header("Location: index.php");
	else{
		$ris=mysql_query("SELECT password,stato FROM utente WHERE username='".$_POST["username"]."';");
		$row=mysql_fetch_array($ris);
		if(!$row)//se lo facevo su $risultato non funzionava correttamente
			header("Location: index.php");
		else
			if($row["password"]!=$_POST["password"])
				header("Location: index.php");
			else{
				if($row["stato"]=="attesa"){
					echo "
						<style>
							.ui-dialog-titlebar-close{
								display: none;
							}						
						</style>
						<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js' type='text/javascript'></script>
						<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type='text/javascript'></script>
						<link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css' type='text/css'/>
						<script>
							$(function() {					
								$( '#dialog-message' ).dialog({
									modal: true
								});
							});
						</script>
						<div id='dialog-message' title='Conferma registrazione!'>
							<p>
								Accedi alla tua email e conferma la registrazione...
								<br />
								Ti stiamo aspettando!
							</p>
						</div>
					";
					header("refresh:5;url=index.php");
				}
				else{
					session_start();
					$ris=mysql_query("SELECT id FROM utente WHERE username='".$_POST["username"]."';") or die(mysql_error());
					$row=mysql_fetch_array($ris);
					$_SESSION["user"]=$row["id"];			
					header("Location: menu.php");
				}
			}
	}
?>