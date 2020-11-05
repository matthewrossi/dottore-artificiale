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
	if(isSet($_SESSION["visita"]))
		mysql_query("DROP TABLE m".$_SESSION["visita"].";") or die(mysql_error());
	session_destroy();
?>
<html>
	<head>
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
	</head>
	<body>
		<div id='dialog-message' title='Logout'>
			<p>
				Ogni visita in sospeso è stata terminata
				<br />
				e sarà visibile nella pagina di gestione dei
				<br />
				pazienti facendo click sul paziente interessato.
				<br />
				Arrivederci!
			</p>
		</div>
	</body>
</html>
<?php
	header("refresh:5;url=./menu.php");
?>