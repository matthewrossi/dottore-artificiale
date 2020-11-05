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
		header("Location: index.php");
	else{
		$ris=mysql_query("SELECT username FROM utente WHERE id='".$_SESSION["user"]."'") or die(mysql_error());
		$row=mysql_fetch_array($ris);
	}
?>
<html>
	<head>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js' type='text/javascript'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type='text/javascript'></script>
		<link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css' type='text/css'/>    
		<script>
			i = 0;
			$(document).ready( 
				function (){
					$(':button').button();
				}			
			);
			function send_msg(){
				$('#dialog').append('<div class=\'msg\'>Tu: '+$('#msg').val()+'</div>');
				$.post(
					'analizza_msg.php',
					$('#sender').serialize(),
					function (data){
						data=$.trim(data);//Forse inutile
						eval(data);
					}
				);
				document.getElementById('msg').value="";
			}
			/*Di per se funziona ma non posso prendere il risultato della 'alert' e quindi andrei sempre a cancellare la tabella*/
			window.onbeforeunload = function() {
				$.post('chiudi_visita.php');
				/*return "In caso vi sia una visita in sospeso questa verrà definitivamente terminata";*/
			}
		</script>
		<style>
			body{
				font-weight: bold;
			}
			th{
				background-color: white;
				background-image: url(./images/bgVisita.gif);
				background-repeat: repeat-x;
				background-position: bottom;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				padding: 6px;
				margin: 3px 3px 0px 3px ;
				vertical-align: middle;
				font-weight: bold;
				text-align:left;
			}
			td{
				background-color: white;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				margin: 3px 6px 3px 6px;
				padding: 6px;
				vertical-align:text-top;
			}
			.first_msg{
				background-color: white;
				border: 1px solid #EEE;
				margin: 0px 0px 0px 0px;
				padding: 6px;
				width: 97.9%;
			}
			.msg{
				background-color: white;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				border-top-width: 0px;
				margin: 0px 0px 0px 0px;
				padding: 6px;
				width: 97.9%;
			}
		</style>
	</head>
	<body>
		<table width='100%'>
			<tr>
				<td id='dialog' height='300px' width='60%' scrolling='yes'>
					<div class='first_msg' height='20px'>
						Dr. Artificiale: Buongiorno 
						<?php 
							$ris=mysql_query("SELECT username FROM utente WHERE id='".$_SESSION["user"]."';") or die(mysql_error());
							$row=mysql_fetch_array($ris);
							echo $row["username"]; 
						?>
						quale paziente vuole visitare?
					</div>
				</td>
				<td id='symptom' width='40%'>
					Non e' stato analizzato alcun sintomo...
				</td>							
			</tr>
			<tr>
				<td align='center'>
					<form id='sender'>
						<input id='visita' name='visita' type='hidden' value='0'/>
						<textarea id='msg' name='msg' cols='69'></textarea>
						<br />
						<input type='button' value='Rispondi' onclick='send_msg()'/>
					</form>
				</td>
				<td id='classification'>
					Se vuoi avere una lista delle malattie possibili devi analizzare qualche sintomo...	
				</td>
			</tr>
		</table>
	</body>
</html>