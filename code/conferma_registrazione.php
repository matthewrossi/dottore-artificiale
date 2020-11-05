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

	$link = mysql_connect("localhost","root","");
	if(!$link){
		echo "Errore nella connessione!";
	}
	$db_selected = mysql_select_db("my_dottore", $link);
	if(!$db_selected){
		echo ("Errore nella selezione del database!");
	}
	$id=$_GET['v'];
	$id=($id/3+178)/41;
	$ris=mysql_query("SELECT stato FROM utente WHERE id=".$id);
	if($ris){
		$row = mysql_fetch_array($ris);
		if($row["stato"]=="attesa"){
			mysql_query("UPDATE utente SET stato='confermato' WHERE id=".$id) or die(mysql_error());//da confermare
			session_start();
			$_SESSION['user']=$id;
			echo "<style>
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
				<div id='dialog-message' title='Registrazione confermata'>
					<p>
						Hai confermato la registrazione.
						<br />
						Sei uno dei nostri!
					</p>
				</div>
			";
			header("refresh:5;url=../menu.php");
		}
		else if($row["stato"]=="confermato")
			header("Location: ../index.php");//è già stato confermato
	}
	else
		header("Location: ../index.php");//non è presente
?>