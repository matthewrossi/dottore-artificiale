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
			echo "alert('Hai gi� in cura questo paziente!')";
	}
	else
		echo "alert('Errore: inserimento non avvenuto!')";	
?>