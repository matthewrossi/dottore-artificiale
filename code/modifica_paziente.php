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
				echo "alert('Vi è già un paziente con quel codice fiscale!');";	
		}
		else
			echo "Permesso negato";
	}
	else
		echo "alert('Errore: modifica non avvenuta!');";
?>