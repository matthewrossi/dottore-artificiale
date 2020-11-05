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

	$conn=mysql_connect("localhost","root","");
	if (! $conn){
		echo ("Errore nella connessione!");
		exit();
	}
	mysql_select_db("my_dottore");
	if(isSet($_POST['new_username'])){
		$ris_select=mysql_query("SELECT * FROM utente WHERE username='".$_POST['new_username']."';");
		$username_ripetuto=mysql_fetch_array($ris_select);
		session_start();
		if($username_ripetuto)
			header("Location: index.php");
		$mail_non_ripetuta = mysql_query("INSERT INTO utente VALUES (NULL,'".$_POST['new_username']."','".$_POST['new_password']."','".$_POST['nome']."','".$_POST['cognome']."','".$_POST['data']."','".$_POST['luogo']."','".$_POST['email']."','attesa');");
		if($mail_non_ripetuta){
			$ris=mysql_query("SELECT id FROM utente WHERE email='".$_POST['email']."'");
			$row = mysql_fetch_array($ris);
			$id=($row["id"]*41-178)*3;
			$link="http://dottore.altervista.org/conferma_registrazione.php/?v=".$id;
			$message="
				<html>
					<title>Dottore | Conferma registrazione</title>
					<body>
						<div style='width:500px;font-size:20;border:solid 2px;padding:10px;background-color:#26F18F;'>
							<p>Grazie ".$_POST['nome']." per esserti registrato sul nostro sito!<br>
							Per iniziare a usufruire dei servizi offerti da dottore.altervista.org conferma <br>
							subito la tua registrazione, servendoti del link sottostante.<br></p>
							<hr/>
							<h5><a href=".$link." >Clicca qui per confermare la tua registrazione!</a></h5>
							<hr/>
							<p>Distinti saluti<br></p>
							<p align=center>Lo Staff di Dottore.</p>
							<hr/>
						</div>
					</body>
				</html>";
			$header="From: Dottore\n";
			$header.= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
			$header.= "Content-Transfer-Encoding: 7bit\n\n";
			$header.= "MIME-Version: 1.0\n";
			mail($_POST['email'], 'Conferma registrazione', $message, $header);
		}
		else
			header("Location: index.php");
		header("Location: index.php");//teoricamente dovrei passare con una get in modo che in index.php spieghi di dover andare alla conferma mail
	}
	else	
		header("Location: index.php");
?>