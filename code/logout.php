<?php
// Copyright (c) 2020 Matthew Rossi
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of
// this software and associated documentation files (the "Software"), to deal in
// the Software without restriction, including without limitation the rights to
// use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
// the Software, and to permit persons to whom the Software is furnished to do so,
// subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
// FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
// COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
// IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
// CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

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