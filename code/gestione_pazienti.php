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
	$ris=mysql_query("
		SELECT *
		FROM paziente
		WHERE id_utente='".$_SESSION["user"]."';		
	") or die(mysql_error());
	$N=mysql_num_rows($ris);
	$row=mysql_fetch_array($ris);
?>
<html>
	<head>
		<title></title>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js' type='text/javascript'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type='text/javascript'></script>
		<link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css' type='text/css'/>    
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<style type='text/css'>
			.visita{
				background-color: white;
				background-image: url(./images/bgVisita.gif);
				background-repeat: repeat-x;
				background-position: bottom;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				padding: 6px;
				cursor: pointer;
				margin: 3px 3px 0px 3px ;
				vertical-align: middle;
				font-weight: bold;
				width: 100%;
			}
			.details{
				background-color: white;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				border-top-width: 0px;
				margin: 0px 6px 3px 3px;/*0px 6px 3px 6px*/
				padding: 6px;
				width: 100%;/*96.4%*/
			}
			th{
				text-align:left;
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
			#visite td{
				background-color: white;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				margin: 3px 6px 3px 6px;
				padding: 6px;
			}
		</style>
		<script language='javascript'>
			var stringFilter, dashboard, table, data;
			google.load('visualization', '1.0', {packages:['controls']});
			google.setOnLoadCallback(drawDashboard);
			// The selection handler.
			// Loop through all items in the selection and concatenate
			// a single message from all of them.
			function drawDashboard() {
				dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));
				// Prepare the data.		
				data = new google.visualization.DataTable();
				data.addColumn('string', 'Codice fiscale');
				data.addColumn('string', 'Nome');
				data.addColumn('string', 'Cognome');
				data.addColumn('date', 'Data di nascita');
				data.addColumn('string', 'Luogo di nascita');
				data.addColumn('string', 'Indirizzo');
				data.addColumn('string', 'Provincia');
				data.addColumn('string', 'Edit');
				<?php
					echo "data.addRows(".$N.");";
					if($N){
						$i=0;
						do{
							$data=explode('-',$row["data_nascita"]);
							echo "
								data.setCell(".$i.",0,'".$row["cf"]."');
								data.setCell(".$i.",1,'".$row["nome"]."');
								data.setCell(".$i.",2,'".$row["cognome"]."');
								data.setCell(".$i.",3,new Date (".$data[0].",".$data[1]."-1,".$data[2]."));
								data.setCell(".$i.",4,'".$row["luogo_nascita"]."');
								data.setCell(".$i.",5,'".$row["indirizzo"]."');
								data.setCell(".$i.",6,'".$row["provincia"]."');
								data.setCell(".$i.",7,'<img id=\'mod\' src=./images/edit.png onclick=set_dialog(this,\'".$row["cf"]."\',\'".$i."\') /><img src=./images/drop.png onclick=del_paziente(\'".$i."\') />');
							";			
							$i++;
						}while($row=mysql_fetch_array($ris));
					}
				?>				
				// Define a StringFilter control for the 'Name' column
				stringFilter = new google.visualization.ControlWrapper({
					'controlType': 'StringFilter',
					'containerId': 'control1',
					'options': {
						'filterColumnLabel': 'Codice fiscale',
						'ui':{
							label: ''
						}
					}
				});
				
				// Define a table visualization
				table = new google.visualization.ChartWrapper({
					'chartType': 'Table',
					'containerId': 'chart1',
					'options': {
						'allowHtml': true
					}
				});	
				
				// Add our selection handler.
				google.visualization.events.addListener(table, 'select', selectHandler);
				
				// Configure the string filter to affect the table contents
				dashboard.bind(stringFilter, table);
				// Draw the dashboard
				dashboard.draw(data);
		}	
		function chg_filter(obj){
			stringFilter.setOption('filterColumnLabel',$(obj).val());
			dashboard.bind(stringFilter, table).draw(data);
		}
		function selectHandler() {
			var selection = table.getChart().getSelection(); 
			var message = '';
			switch (selection.length){
				case 0:
					$('#visite').text('Nessun paziente selezionato');
				break;
				case 1:
					$.post(
						'mostra_visite.php',
						{ cf: data.getFormattedValue(selection[0].row, 0) },
						function (data){
							$('#visite').html(data);
							$('.details').each(
								function (){
									$(this).hide();
								}
							);
						}
					);
				break;
				default:
					$('#visite').text('Puoi vedere le visite di un solo paziente per volta');
				break;
			}
		}
		$(document).ready(
			function() {
				var allFields = $([]).add($('#cf')).add($('#nome')).add($('#cognome')).add($('#data_nascita')).add($('#luogo_nascita')).add($('#indirizzo')).add($('#provincia'));
				$('#dialog').dialog({
					autoOpen: false,
					show: 'fade',
					hide: 'fade',
					resizable: false,
					width: '40%',
					modal: true,
					close: function() {
						allFields.val('');
					}					
				});
				$('#data_nascita').datepicker({
						dateFormat: 'yy/mm/dd',
						maxDate: '+0D',
						showOtherMonths: true,
						selectOtherMonths: true,
						changeMonth: true,
						changeYear: true,
						showAnim: 'slideDown',
						yearRange: "-120:+0"
				});	
				$(':button').button();
			}
		);
		function showDetails(obj){
			$(obj).next().toggle();
		}
		function cnvrt_month(str){
			switch(str){
				case 'gen':
					return "01";
				break;
				case 'feb':
					return "02";
				break;
				case 'mar':
					return "03";
				break;
				case 'apr':
					return "04";
				break;
				case 'mag':
					return "05";
				break;
				case 'giu':
					return "06";
				break;
				case 'lug':
					return "07";
				break;
				case 'ago':
					return "08";
				break;
				case 'set':
					return "09";
				break;
				case 'ott':
					return "10";
				break;
				case 'nov':
					return "11";
				break;
				case 'dic':
					return "12";
				break;
			}
		}
		function set_dialog(obj, cf, row){
			if($(obj).attr('id')=='add'){
				$('#dialog').dialog({title: 'Aggiungi paziente'});
				$('#submit').val('Aggiungi').attr('onclick','add_paziente()');
			}
			else 
				if($(obj).attr('id')=='mod'){
					row=parseInt(row);
					$('#dialog').dialog({title: 'Modifica paziente'});
					$('#submit').val('Salva modifiche').attr('onclick','mod_paziente(\''+cf+'\','+row+')');
					$('#cf_vecchio').val(data.getFormattedValue(row, 0));
					$('#cf').val(data.getFormattedValue(row, 0));
					$('#nome').val(data.getFormattedValue(row, 1));
					$('#cognome').val(data.getFormattedValue(row, 2));
					var date=data.getFormattedValue(row,3).split("/");
					$('#data_nascita').val(date[2]+'/'+cnvrt_month(date[1])+'/'+date[0]);
					$('#luogo_nascita').val(data.getFormattedValue(row, 4));
					$('#indirizzo').val(data.getFormattedValue(row, 5));
					$('#provincia').val(data.getFormattedValue(row, 6));
				}					
			$('#dialog').dialog('open');
		}
		function add_paziente(){
			$.post(
				'aggiungi_paziente.php',
				$('#dialog').serialize(),
				function (php_data){
					php_data=$.trim(php_data);//forse inutile
					eval(php_data);
					$('#dialog').dialog('close');
				}
			);		
		}
		function mod_paziente(cf,row){
			$.post(
				'modifica_paziente.php',
				$('#dialog').serialize(),
				function (php_data){
					php_data=$.trim(php_data);//forse inutile
					eval(php_data);
					$('#dialog').dialog('close');
				}
			);
		}
		function del_paziente(row){
			row=parseInt(row);
			$.post(
				'rimuovi_paziente.php',
				{ cf: data.getFormattedValue(row, 0) },
				function (php_data){
					php_data=$.trim(php_data);//forse inutile
					eval(php_data);
				}
			);
		}
		</script>
	</head>
	<body>
		<table id='dashboard_div' style='position: realtive;' width=100%>
			<tr>
				<td width='110px'>
					<select onChange='chg_filter(this)'>
						<option value='Codice fiscale' selected='selected'>
							Codice fiscale
						</option>
						<option value='Nome'>
							Nome
						</option>
						<option value='Cognome'>
							Cognome
						</option>
					</select>
				</td>
				<td id='control1'></td>
			</tr>
			<tr>
				<td id='chart1' colspan='2' width=100%></td>
			</tr>
			<tr>
				<td colspan='2' style='text-align: center;'>
					<input id='add' type='button' onclick='set_dialog(this)' value='Inserisci nuovo paziente' style='font-size: 80%;'/>
				</td>
			</tr>
		</table>
		<div id='visite' style='width: 98%;'>
			Nessun paziente selezionato
		</div>
		<form id='dialog'>
			<table>
				<tr>
					<td>Codice fiscale: </td>
					<td>
						<input id='cf_vecchio' type='hidden' name='cf_vecchio'/>
						<input id='cf' type='text' name='cf' size='30' maxlength='16'/>
					</td>
				</tr>
				<tr>
					<td>Nome: </td>
					<td><input id='nome' type='text' name='nome' size='30' maxlength='50'/></td>
				</tr>
				<tr>
					<td>Cognome: </td>
					<td><input id='cognome' type='text' name='cognome' size='30' maxlength='50'/></td>
				</tr>
				<tr>
					<td>Data di nascita: </td>
					<td><input id='data_nascita' type='text' name='data_nascita' size='30' maxlength='10'/></td>
				</tr>
				<tr>
					<td>Luogo di nascita: </td>
					<td><input id='luogo_nascita' type='text' name='luogo_nascita' size='30' maxlength='50'/></td>
				</tr>
				<tr>
					<td>Indirizzo: </td>
					<td><input id='indirizzo' type='text' name='indirizzo' size='30' maxlength='100'/></td>
				</tr>
				<tr>
					<td>Provincia: </td>
					<td><input id='provincia' type='text' name='provincia' size='30' maxlength='50'/></td>
				</tr>
					<td align='center' colspan='2'>
						<input id='submit' type='button' value='Aggiungi'/>
						<input type='button' value='Annulla' onclick=$('#dialog').dialog('close') />
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>