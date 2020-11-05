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
		echo ("alert('Errore nella connessione!');");
		exit();
	}
	$db_selected=mysql_select_db("my_dottore",$link);
	if(!$db_selected){
		echo ("alert('Errore nella selezione del database!');");
		exit();
	}
	session_start();
	if(!isSet($_SESSION["user"]) && !isSet($_POST["msg"]))
		exit();
	else{
		$id_utente=$_SESSION["user"];
		if(!$_POST["visita"]){//se è settato $_POST["msg"] è settato anche $_POST["visita"]
			//RICONOSCO PAZIENTE IN VISITA
			$ris=mysql_query("SELECT id,cf FROM paziente P WHERE id_utente='".$id_utente."';") or die("alert(".mysql_error().");");
			if($row=mysql_fetch_array($ris)){
				do
					$match=preg_match("/".$row["cf"]."/i",$_POST["msg"]);
				while(!$match && $row=mysql_fetch_array($ris));
				if($match){			
					$cf=$row["cf"];
					$_SESSION["paziente"]=$row["id"];					
					mysql_query("INSERT INTO visita VALUES (NULL,NOW(),".$row["id"].");") or die("alert(".mysql_error().");");
					$ris=mysql_query("SELECT id FROM visita WHERE id_paziente='".$row["id"]."' ORDER BY data DESC,id DESC;") or die("alert(".mysql_error().");");//potrebbe dare degli errori prossimamente
					$row=mysql_fetch_array($ris);
					$_SESSION["visita"]=$row["id"];
					mysql_query("
						CREATE TABLE m".$row["id"]."
						SELECT * FROM malattia;
					")	or die("alert(".mysql_error().");");
					//mostro paziente riconosciuto e imposto per successivi sintomi/malattie
					echo "
						$('#symptom').html('Stai visitando il paziente con codice fiscale: ".$cf."<ul><li>E\' affetto dai seguenti sintomi:</li><table id=\'affetto\'><th width=\'30%\'>Nome</th><th width=\'70%\'>Descrizione</th></table><li>Non e\' affetto dai seguenti sintomi:</li><table id=\'non_affetto\'><th width=\'30%\'>Nome</th><th width=\'70%\'>Descrizione</th></table></ul>');
						$('#visita').val('1');
					";					
				}
				else{
					echo "alert('Non hai in cura il paziente specificato');";
					exit();
				}
			}
			else{
				echo "alert('Non hai nessun paziente in cura');";
				exit();
			}
		}
		else{
			if(!isSet($_SESSION["paziente"]) && !isSet($_SESSION["visita"]) && !isSet($_SESSION["sintomo"]) && !isSet($_SESSION["domanda"]))
				exit();
			else{
				//RICONOSCO SE AFFETTO, NON AFFETTO O INSERIMENTO ERRATO
				//dovrei verificare anche se vuole terminare anticipatamente la visita con altre expreg
				$ris=mysql_query("SELECT E.pattern, E.affetto FROM expreg E WHERE E.id_domanda='".$_SESSION["domanda"]."';");
				$match = 0;
				while(!$match && $row=mysql_fetch_array($ris))
					$match=preg_match("/".$row["pattern"]."/i",$_POST["msg"]);
				if($match){
					$affetto=$row["affetto"];
					mysql_query("INSERT INTO presenta VALUES ('".$_SESSION["visita"]."','".$_SESSION["sintomo"]."','".$row["affetto"]."');") or die("alert(".mysql_error().");");
					$ris=mysql_query("SELECT nome,descrizione FROM sintomo WHERE id='".$_SESSION["sintomo"]."';");
					$row=mysql_fetch_array($ris);
					//ESCLUDO LE MALATTIE IN BASE AL NUOVO SINTOMO
					if($affetto){
						mysql_query("
							DELETE FROM m".$_SESSION["visita"]."
							WHERE NOT EXISTS(
								SELECT S.id
								FROM sintomo S INNER JOIN identifica I ON S.id=I.id_sintomo
								WHERE I.id_malattia=m".$_SESSION["visita"].".id AND S.id='".$_SESSION["sintomo"]."'
							);
						") or die("alert(".mysql_error().");");		
						$nome=addslashes(htmlentities($row["nome"],ENT_COMPAT,'UTF-8'));
					    	$desc=addslashes(htmlentities($row["descrizione"],ENT_COMPAT,"UTF-8"));
						//aggiungo ai sintomi di cui è affetto il paziente
						echo "$('#affetto').append('<tr><td>".$nome."</td><td>".$desc."</td></tr>');";
					}
					else{
						mysql_query("
							DELETE FROM m".$_SESSION["visita"]."
							WHERE EXISTS(
								SELECT S.id
								FROM sintomo S INNER JOIN identifica I ON S.id=I.id_sintomo
								WHERE I.id_malattia=m".$_SESSION["visita"].".id AND S.id='".$_SESSION["sintomo"]."'
							);
						") or die("alert(".mysql_error().");");
						$nome=addslashes(htmlentities($row["nome"],ENT_COMPAT,'UTF-8'));
					    	$desc=addslashes(htmlentities($row["descrizione"],ENT_COMPAT,"UTF-8"));
						//aggiungo ai sintomi di cui è non affetto il paziente 
						echo "$('#non_affetto').append('<tr><td>".$nome."</td><td>".$desc."</td></tr>');";
					}
					//aggiorno malattie possibili
					$ris=mysql_query("SELECT * FROM m".$_SESSION["visita"].";") or die("alert(".mysql_error().");");
					echo "$('#classification').html('Elenco malattie possibili...<br /><table><tr><th>Nome</th><th>Descrizione</th></tr>";
					while($row=mysql_fetch_array($ris)){
						$nome=addslashes(htmlentities($row["nome"],ENT_COMPAT,'UTF-8'));
					    	$desc=addslashes(htmlentities($row["descrizione"],ENT_COMPAT,"UTF-8"));
						echo "<tr><td>".$nome."</td><td>".$desc."</td></tr>";
					}
					echo "</table>');";
				}
				else{
					echo "alert('La frase inserita non corrisponde ad alcun pattern! Ricomponi il messaggio!');";
					exit();
				}
			}				
		}
		//CERCO NUOVA DOMANDA
		$ris=mysql_query("SELECT COUNT(*) AS N FROM m".$_SESSION["visita"].";") or die("alert(".mysql_error().");");
		$row=mysql_fetch_array($ris);
		if($row["N"]>1){
			mysql_query("
				CREATE TABLE s".$_SESSION["visita"]." SELECT I.id_sintomo AS id, COUNT(*) AS affetto, ".$row["N"]." - COUNT(*) AS non_affetto
				FROM m".$_SESSION["visita"]." M INNER JOIN identifica I ON M.id = I.id_malattia
				GROUP BY I.id_sintomo
				HAVING I.id_sintomo!=ALL(
					SELECT S.id
					FROM sintomo S
					INNER JOIN presenta P ON S.id = P.id_sintomo
					WHERE P.id_visita ='".$_SESSION["visita"]."'
				);
			") or die("alert(".mysql_error().");");
			$ris=mysql_query("
				SELECT S.id
				FROM s".$_SESSION["visita"]." S
				WHERE ABS(S.affetto-S.non_affetto) = (
					SELECT MIN(ABS(S.affetto-S.non_affetto)) 
					FROM s".$_SESSION["visita"]." S
				);
			") or die("alert(".mysql_error().");");
			mysql_query("DROP TABLE s".$_SESSION["visita"].";") or die("alert(".mysql_error().");");			
			$nriga=rand(0,mysql_num_rows($ris)-1);
			for($i=0;$i<$nriga;$i++)
				mysql_fetch_array($ris);
			$row=mysql_fetch_array($ris);
			$_SESSION["sintomo"]=$row["id"];
			$ris=mysql_query("
				SELECT id,domanda
				FROM domanda
				WHERE id_sintomo='".$row["id"]."';
			") or die("alert(".mysql_error().");");
			$nriga=rand(0,mysql_num_rows($ris)-1);
			for($i=0;$i<$nriga;$i++)
				mysql_fetch_array($ris);
			$row=mysql_fetch_array($ris);
			$_SESSION["domanda"]=$row["id"];
			$domanda=addslashes(htmlentities($row["domanda"],ENT_COMPAT,"UTF-8"));
			echo "$('#dialog').append('<div class=\'msg\'>Dr. Artificiale: ".$domanda."</div>');";
		}
		else{
			mysql_query("DROP TABLE m".$_SESSION["visita"].";") or die("alert(".mysql_error().");");
			unset($_SESSION["sintomo"], $_SESSION["domanda"], $_SESSION["visita"], $_SESSION["paziente"]);
			$ris=mysql_query("SELECT username FROM utente WHERE id='".$_SESSION["user"]."';") or die("alert(".mysql_error().");");
			$row=mysql_fetch_array($ris);
			echo "
				alert('Visita terminata! Potrai vederne il resoconto nella pagina di gestione dei pazienti, clickando sul paziente interessato');
				$('#dialog').html('<div class=\'first_msg\'>Dr. Artificiale: Buongiorno ".$row["username"].", quale paziente vuole visitare?</div>');
				$('#symptom').text('Non e\' stato analizzato alcun sintomo...');
				$('#classification').text('Se vuoi avere una lista delle malattie possibili devi analizzare qualche sintomo...');
				$('#visita').val('0');
			";
		}
	}
?>