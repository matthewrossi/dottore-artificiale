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
	$ris=mysql_query("SELECT * FROM utente WHERE id='".$_SESSION["user"]."';") or die(mysql_error());
    	$row=mysql_fetch_array($ris);
?>
<html>
	<head>
		<title></title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"></script>
		<link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css' type='text/css'/>
		<style type="text/css">
			.form_edit{
				display: none;
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
				margin: 3px 3px 0px 3px;
				vertical-align: middle;
				font-weight: bold;
				width: 100%;
				text-align:left;
			}
			td{
				background-color: white;
				border-width: 1px;
				border-style: solid;
				border-color: #EEE;
				margin: 3px 6px 3px 6px;
				padding: 6px;
			}
			label.error{
				color: #FF0000;
				margin: 0px 0px 10px 0px;
			}
		</style>
		<script language="javascript">
			$(document).ready(
				function (){
					$(":button").button();
					$.validator.addMethod(
						"regexp",
						function(value, element, regexp) {
							var re = new RegExp(regexp);
							return this.optional(element) || re.test(value);
						},
						"Please check your input"
					);
					$("#nome").validate({
						rules:{
							value:{
								required: true,
								regexp: "^[A-Za-z &nbsp]*$"
							}
						},
						messages:{
							value:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Nome non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Nome malformato"
							}						
						}
					});
					$("#cognome").validate({
						rules:{
							value:{
								required: true,
								regexp: "^[A-Za-z &nbsp]*$"
							}
						},
						messages:{
							value:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Nome non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Nome malformato"
							}						
						}
					});
					$("#email").validate({
						rules:{
							value:{
								required: true,
								email: true,
								remote:{
									url: "check-mod-mail.php",
									type: "post"
								}
							}
						},
						messages:{
							value:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Email non inserita",
								email: "<img src='./images/error.png' width='20px' height='20px'/> Email malformata",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Email già in uso per una altro profilo"
							}
						}
					});
					$("#username").validate({
						rules: {
							value:{
								required: true,
								regexp: "^[\\w_-]{5,20}$",
								remote:{
									url: "check-mod-username.php",
									type: "post"
								}
							}
						},
						messages:{
							value:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Username non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Username malformato",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Username non disponibile"
							}
						}
					});
					$("#password").validate({
						rules: {
							old_pswd:{
								required: true,
								regexp: "^[\\S]{6,36}$",
								remote:{
									url: "check-mod-password.php",
									type: "post"
								}
							},
							value:{
								required: true,
								regexp: "^[\\S]{6,36}$"
							},
							cnfr_pswd:{
								required: true,
								equalTo: "#value"
							}
						},
						messages:{
							old_pswd:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non inserita",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Password errata",//non so se farlo
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Password malformata"
							},
							value:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non inserita",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Password malformata"
							},
							cnfr_pswd:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non confermata",
								equalTo: "<img src='./images/error.png' width='20px' height='20px'/> Password non confermata"
							}
						}
					});
					$("#data").validate({
						rules: {
							value:{
								regexp: "^\\d{4,4}\/((0[1-9])|(1[0-2]))\/((0[1-9])|([1-2][0-9])|(3[0-1]))$"
							}
						},
						messages:{
							value:{
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Data malformata"
							}
						}
					});
					$("#luogo").validate({
						rules: {
							value:{
								regexp: "^[A-Za-z &nbsp]*$"
							}
						},
						messages:{
							value:{
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Luogo malformato"
							}
						}
					});
					$('.save').click(
						function(){
							if($(this.form).valid()){
								save_mod(this);
							}
						}
					);
				}
			);
			function edit_mode(obj,edit){
				if(edit){
					if($(obj).parent('td').next().children('div').is(':visible')){
						$(obj).parent('td').next().children('form').children('input').next().val($(obj).parent('td').next().children('div').text().trim());					
						$('form.form_edit').filter(':visible').each(
							function (){
								edit_mode($(this).children('input'),false);
							}
						);
						$(obj).parent('td').next().children('div').hide();
						$(obj).parent('td').next().children('form').show();
					}
				}
				else{
					$(obj).parent('form').prev().show();
					$(obj).parent('form').hide();
				}
			}
			function save_mod(obj){
				$.post(
					"modifica_profilo.php",
					$(obj).parent('form').serialize(),
					function (data){						
						$(obj).parent('form').prev().text(data);					
						edit_mode(obj,false);
					}
				);
			}
			function save_pswd_mod(obj){
				$.post(
					"modifica_profilo.php",
					$(obj).parent('form').serialize(),
					function (data){
						$(obj).parent('form').prev().text('');
						for(var i=0;i<data.length;i++)
							$(obj).parent('form').prev().html($(obj).parent('form').prev().text()+'&#9679;');
						edit_mode(obj,false);
					}
				);
			}
		</script>
	</head>
	<body>
		<table>
			<tr id="title">
				<th  colspan='2'>
					Dati personali
				</th>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Nome<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							echo $row["nome"];
						?>
					</div>
					<form class="form_edit" id="nome">
						<input name="name" type="hidden" value="nome"/>
						<input name="value" type="text" maxlength="50"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Cognome<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div > 
						<?php
							echo $row["cognome"];
						?>
					</div>
					<form class="form_edit" id="cognome">
						<input name="name" type="hidden" value="cognome"/>
						<input name="value" type="text" maxlength="50"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Email<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							echo $row["email"];
						?>
					</div>
					<form class="form_edit" id="email">
						<input name="name" type="hidden" value="email"/>
						<input name="value" type="text" maxlength="50" id="mail"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Username<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							echo $row["username"];
						?>
					</div>
					<form class="form_edit" id="username">
						<input name="name" type="hidden" value="username"/>
						<input name="value" type="text" maxlength="20"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Password<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							for($i=0;$i<strlen($row["password"]);$i++)
								echo "&#9679;";
						?>
					</div>
					<form class="form_edit" id="password">
						<input name="name" type="hidden" value="password"/>
						<label>Password attuale:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>							
						<input name="old_pswd" type="password" maxlength="36"/>
						<br />
						<label>Nuova password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>									
						<input name="value" type="password" maxlength="36" id="value"/>
						<br />
						<label>Conferma nuova password:</label>				
						<input name="cnfr_pswd" type="password" maxlength="36"/>
						<br />
						<button type="button" onclick="save_pswd_mod(this)" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Data di nascita<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							echo $row["data_nascita"];
						?>
					</div>
					<form class="form_edit" id="data">
						<input name="name" type="hidden" value="data_nascita"/>
						<input name="value" type="text" maxlength="10"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:text-top;">Località<img src="./images/edit.png" onclick="edit_mode(this,true)" /></td>
				<td>
					<div>
						<?php
							echo $row["residenza"];
						?>
					</div>
					<form class="form_edit" id="luogo">
						<input name="name" type="hidden" value="residenza" />
						<input name="value" type="text" maxlength="50"/>
						<br />
						<button type="button" class="save" style="font-size: 70%;">Salva</button>
						<button type="button" onclick="edit_mode(this,false)" style="font-size: 70%;">Annulla</button>
					</form>
				</td>
			</tr>
		</table>
	</body>
</html>