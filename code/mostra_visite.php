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
	if(isSet($_SESSION["user"])){
		if(isSet($_POST["cf"])){
		    	$ris_out=mysql_query("
		    		SELECT V.id,V.data
		    		FROM visita V INNER JOIN paziente P ON V.id_paziente=P.id
		    		WHERE P.id_utente='".$_SESSION["user"]."' AND P.cf='".$_POST["cf"]."'
		    		ORDER BY data DESC,id DESC;
		    	") or die(mysql_error());
			if(!($row_out=mysql_fetch_array($ris_out)))
				echo "Il paziente selezionato non ha visite";
			else
				do{
					$id_visita=$row_out["id"];
					echo "
						<div class='visita' onclick=showDetails(this)>
							".$row_out["data"]."
						</div>
						<div class='details'>
					";
					$ris=mysql_query("
				    		SELECT S.nome,S.descrizione
				    		FROM presenta P INNER JOIN sintomo S ON S.id=P.id_sintomo
				    		WHERE P.id_visita='".$id_visita."' AND affetto;
				    	") or die(mysql_error());
				    	echo "<ul>";
				    	if($row=mysql_fetch_array($ris)){
					    	echo "
				    			<li>E' affetto dai seguenti sintomi:</li>
				    			<table id=\'affetto\'>
				    				<th>Nome</th>
				    				<th>Descrizione</th>
					    	";
					    	do{
					    		echo "
					    			<tr>
					    				<td>".$row["nome"]."</td>
					    				<td>".$row["descrizione"]."</td>
					    			</td>
					    		";
					    	}while($row=mysql_fetch_array($ris));
					    	echo "</table>";
					}
					$ris=mysql_query("
				    		SELECT S.nome,S.descrizione
				    		FROM presenta P INNER JOIN sintomo S ON S.id=P.id_sintomo
				    		WHERE P.id_visita='".$id_visita."' AND !affetto;
				    	") or die(mysql_error());
					if($row=mysql_fetch_array($ris)){
						echo "				    	
				    			<li>Non e' affetto dai seguenti sintomi:</li>
				    			<table>
				    				<th>Nome</th>
				    				<th>Descrizione</th>
					    	";					    	
					    	do{
					    		echo "
					    			<tr>
					    				<td>".$row["nome"]."</td>
					    				<td>".$row["descrizione"]."</td>
					    			</td>
					    		";
					    	}while($row=mysql_fetch_array($ris));
					    	echo "</table>";
						    		
					}
					$ris=mysql_query("
						SELECT M.nome, M.descrizione
						FROM malattia M
						WHERE NOT EXISTS (
							SELECT S_out.id
							FROM sintomo S_out
							WHERE EXISTS (
								SELECT S_in.id
								FROM sintomo S_in INNER JOIN presenta P ON S_in.id = P.id_sintomo
								WHERE P.id_visita =".$id_visita." AND affetto AND S_in.id = S_out.id
							)
							AND NOT EXISTS (
								SELECT S_in.id
								FROM sintomo S_in INNER JOIN identifica I ON S_in.id = I.id_sintomo
								WHERE I.id_malattia = M.id AND S_in.id = S_out.id
							)
						)
						AND NOT EXISTS (
							SELECT S_out.id
							FROM sintomo S_out
							WHERE EXISTS (
								SELECT S_in.id
								FROM sintomo S_in INNER JOIN presenta P ON S_in.id = P.id_sintomo
								WHERE P.id_visita =".$id_visita." AND !affetto AND S_in.id = S_out.id
							)
							AND EXISTS (
								SELECT S_in.id
								FROM sintomo S_in INNER JOIN identifica I ON S_in.id = I.id_sintomo
								WHERE I.id_malattia = M.id AND S_in.id = S_out.id
							)
						)
				    	") or die(mysql_error());
					if($row=mysql_fetch_array($ris)){
						echo "				    	
				    			<li>Le malattie possibili sono:</li>
				    			<table>
				    				<th>Nome</th>
				    				<th>Descrizione</th>
					    	";					    	
					    	do{
					    		echo "
					    			<tr>
					    				<td>".$row["nome"]."</td>
					    				<td>".$row["descrizione"]."</td>
					    			</td>
					    		";
					    	}while($row=mysql_fetch_array($ris));
					    	echo "</table>";
						    		
					}
					echo "</ul>
						    	</div>
					";
				}while($row_out=mysql_fetch_array($ris_out));
		}
		else
			echo "Errore nella visulizzazione delle visite";
	}
	else
		header("Location: index.php");
?>