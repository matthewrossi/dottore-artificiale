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

	session_start();
	if(IsSet($_SESSION["user"]))
		header("Location: menu.php")
?>
<html>
	<head>
		<title>Dottore - Home</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"></script>
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css" type="text/css"/>
		<style>
			label.error{
				color: #FF0000;
				margin: 0px 0px 10px 0px;
			}
			body{
				margin:0px;
				padding:0px;
				background-color:#f0f0f0;
				font-family:Arial;
				position: absolute;
				width: 1024px;
			}
			.title{
				position:relative;
				top: -75px;
				left: -20px;
				text-align:right;
				text-transform:capitalize;
				font-size: 30px;
				color: white;
			}
			.header{
				width: 1024px;
				height: 125px;
				border-radius: 10px;
				-webkit-border-bottom-left-radius: 10px;
				-moz-box-shadow: 0 1px 3px #555;
				-webkit-box-shadow: 0 1px 3px #555;
			}
			.tab{
				width: 920px;
				border-radius: 10px;
				position:absolute;
				top: 170px; 
				left: 50px; 
			}
			.introduzione{
				padding:10px;
				width:50%;
				font-size:20pt;
			}
			.tab1{
				padding:10px;
				font-size:14pt;
			}
			.tab2{
				padding:10px;
				font-size:14pt;
			}
			.tab3{
				padding:10px;
				font-size:14pt;
			}
		</style>
		<script language='javascript'>
			var margine;
			function set_window(){
				margine=(window.innerWidth-1024)/2;
				if(margine>0)
					$('body').css({'left': margine});
				else
					$('body').css({'left': 0});
			}
			$(document).ready(
				function (){
					set_window();
					$("#tabs").tabs();	
					$("button").button();
					$("#data").datepicker({
						dateFormat: "yy/mm/dd",
						maxDate: "+0D",
						showOtherMonths: true,
						selectOtherMonths: true,
						changeMonth: true,
						changeYear: true,
						showAnim: "slideDown",
						yearRange: "-120:+0"
					});					
					$.validator.addMethod(
							"regexp",
							function(value, element, regexp) {
								var re = new RegExp(regexp);
								return this.optional(element) || re.test(value);
							},
							"Please check your input"
					);					
					$("#login_form").validate({
						rules: {
							username: {
								required: true,
								regexp: "^[\\w_-]{5,20}$",
								remote:{
									url: "check-username.php",
									type: "post"
								}
							},
							password: {
								required: true,
								regexp: "^[\\S]{6,36}$",
								remote:{
									url: "check-password.php",
									type: "post",
									data:{
										username: function(){
											return $("#username").val();
										}
									}
								}
							}
						},
						messages:{
							username:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Username non inserito",								
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Username malformato",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Username inesistente"//non so se farlo
							},
							password:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non inserita",								
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Password malformata",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Password errata"//non so se farlo
							}							
						}
					});
					$("#reg_form").validate({
						rules: {
							nome:{
								required: true,
								regexp: "^[A-Za-z &nbsp]*$"
							},
							cognome:{
								required: true,
								regexp: "^[A-Za-z &nbsp]*$"
							},
							email:{
								required: true,
								email: true,
								remote:{
									url: "check-mail.php",
									type: "post"
								}
							},
							new_username:{
								required: true,
								regexp: "^[\\w_-]{5,20}$",
								remote:{
									url: "check-new_username.php",
									type: "post"
								}
							},
							new_password:{
								required: true,
								regexp: "^[\\S]{6,36}$"
							},
							cnfrm_password:{
								required: true,
								equalTo: "#new_password"
							},
							data:{
								regexp: "^\\d{4,4}\/((0[1-9])|(1[0-2]))\/((0[1-9])|([1-2][0-9])|(3[0-1]))$"
							},
							luogo:{
								regexp: "^[A-Za-z &nbsp]*$"
							}
						},
						messages:{
							nome:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Nome non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Nome malformato"
							},
							cognome:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Cognome non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Cognome malformato"
							},
							email:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Email non inserita",
								email: "<img src='./images/error.png' width='20px' height='20px'/> Email malformata",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Email già in uso per una altro profilo"
							},
							new_username:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Username non inserito",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Username malformato",
								remote: "<img src='./images/error.png' width='20px' height='20px'/> Username non disponibile"
							},
							new_password:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non inserita",
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Password malformata"
							},
							cnfrm_password:{
								required: "<img src='./images/error.png' width='20px' height='20px'/> Password non confermata",
								equalTo: "<img src='./images/error.png' width='20px' height='20px'/> Password non confermata"
							},
							data:{
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Data malformata"
							},
							luogo:{
								regexp: "<img src='./images/error.png' width='20px' height='20px'/> Luogo malformato"
							}
						}
					});
				}
			);
			$(window).resize(
				function (){
					set_window();
				}
			);
		</script>
	</head>
	<body>
		<img class="header" src="./images/header.jpg"/>
		<div class="title">il dottore artificiale</div>
		<div class="tab">			
			<div class='introduzione'>
				Benvenuto su Il Dottore Artificiale!
			</div>
			<div class='tab1'>
				Una volta effettuato il login, o registrandosi se ne siete tuttora sprovvisti,
				potrete accedere ai servizzi offerti dal nostro sito.  
			</div>
			<table class='tab2'>
				<tr>
					<td align='center'><img src='./images/gestione_pazienti.png'/></td>
					<td width='60%'>Gestione e riepilogo dei pazienti, per poter gestire le visite di più persone a te care e averle a portata di click!</td>
				</tr>
			</table>
			<table class='tab3'>
				<tr>					
					<td width='60%'>Eseguire visite mediche semplici da casa e poter sapere le malattie di cui si è affetti. <span style='color: red;'>Nessun medico ha garantito il suo funzionamento!!</span></td>
					<td align='center'><img src='./images/esegui_diagnosi.png' /></td>
				</tr>
			</table>
			<div id="tabs" align='center'>
				<ul>
					<li><a href="#login_form">Log-in</a></li>
					<li><a href="#reg_form">Registrati</a></li>
				</ul>
				<form id='login_form' method='post' action='login.php'>
					<table>
						<tr>
							<td>
								<label for='username'>Username:</label>	
							</td>
							<td>
								<input id='username' type='text' name='username' maxlength='20'/>
							</td>
						</tr>
						<tr>
							<td>
								<label for='password'>Password:</label>
							</td>
							<td>							
								<input id='password' type='password' name='password' maxlength='36'/>
							</td>							
						</tr>
						<tr>
							<td align='center' colspan='2' >
								<button type='submit'>Accedi</button>
							</td> 
						</tr>
					</table>
				</form>
				<form id='reg_form' method='post' action='registrazione.php'>
					<table>
						<tr>
							<td>
								<label for='nome'>*Nome:</label>
							</td>
							<td>							
								<input type='text' id='nome' name='nome' maxlength='50'/>
							</td>
						</tr>
						<tr>
							<td>
								<label for='cognome'>*Cognome:</label>
							</td>
							<td>							
								<input type='text' id='cognome' name='cognome' maxlength='50'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='email'>*Email:</label>
							</td>
							<td>							
								<input type='text' id='email' name='email' maxlength='50'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='new_username'>*Username:</label>
							</td>
							<td>							
								<input type='text' id='new_username' name='new_username' maxlength='20'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='new_password'>*Password:</label>
							</td>
							<td>							
								<input type='password' id='new_password' name='new_password' maxlength='36'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='confrm_password'>*Conferma password:</label>
							</td>
							<td>							
								<input type='password' id='confrm_password' name='cnfrm_password' maxlength='36'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='data'>Data di nascita:</label>
							</td>
							<td>							
								<input type='text' id='data' name='data' maxlength='10'/>
							</td>								
						</tr>
						<tr>
							<td>
								<label for='luogo'>Luogo di residenza:</label>
							</td>
							<td>							
								<input type='text' id='luogo' name='luogo' maxlength='50'/>
							</td>								
						</tr>
						<tr>
							<td align='center' colspan='2'>
								<button type='submit' >Registrati</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>