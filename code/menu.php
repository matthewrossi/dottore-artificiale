<?php
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
?>
<html>
	<head>
        	<title>Dottore - Menu</title>
        	<link rel="stylesheet" href="css/new_style.css" type="text/css" charset="utf-8"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/overcast/jquery-ui.css" type="text/css"/>    
		<style>
			body{
				background-color: #F0F0F0;
				margin:0px;
				padding:0px;
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
				width: 1024px;
				border-radius: 10px;
				position:absolute;
				top: 230; 
				left: 0; 
				display: none;
			}
		</style>
        	<script language="JavaScript">
			var margine;
            		$(window).load(
				function() {					
					var d=1000;
					var i=0,IDs=new Array();
					margine=(window.innerWidth-1024)/2;
					if(margine>0)
						$('body').css({'left': margine});
					else
						$('body').css({'left': 0});
					$('#menu > li').each(function(){
						IDs[i]=$(this).children('img').attr('class');
						i++;
					});
					$('#menu > li').hover(
						function () {
							var $this = $(this);
							$('img',$this).show();
							$('a',$this).addClass('hover');
							$('img',$this).stop().animate({'top':'40px'},300).css({'zIndex':'10'});							
						},
						function () {
							var $this = $(this);
							$('a',$this).removeClass('hover');
							$('img',$this).stop().animate({'top':'-5px'},800,function(){$('img',$this).hide();}).css({'zIndex':'-1'});
						}
					);
					$('#menu > li').click(
						function (){
							for(var j=0;j<5;j++)
								if($(this).children('img').attr('class')!=IDs[j])
									$('#'+IDs[j]).hide();
								else
									$('#'+IDs[j]).show();							
						}
					);
				}
			);
			$(window).resize(
				function (){
					margine=(window.innerWidth-1024)/2;
					if(margine>0)
						$('body').css({'left': margine});
					else
						$('body').css({'left': 0});
				}
			);
        	</script>
	</head>
    	<body>
		<div class="">Benvenuto, 
			<?php
				session_start();
				if(IsSet($_SESSION["user"])){
					$ris=mysql_query("SELECT username FROM utente WHERE id='".$_SESSION["user"]."';") or die(mysql_error());
					$row=mysql_fetch_array($ris);
					echo $row["username"];
				}
				else
					header("Location: index.php");
			?>
		</div>
		<img class="header" src="./images/header.jpg"/>
		<div class="title">il dottore artificiale</div>
		<div class="navigation">
			<ul class="menu" id="menu">
				<li><img class="profilo" src="./images/dottore.png"/><a href="#" class="first">Gestione profilo</a></li>
				<li><img class="pazienti" src="./images/paziente.png"/><a href="#">Gestione pazienti</a></li>
				<li><img class="diagnosi" src="./images/diagnosi.png"/><a href="#">Esegui diagnosi</a></li>
				<li><img class="logout" src="./images/logout.png"/><a href="./logout.php" class="last">Logout</a></li>
			</ul>
		</div>
		<div id="profilo" class="tab" style="display: block;">
			<iframe id='gestione_profilo' src="./gestione_profilo.php" scrolling="no" frameborder="0" width='100%' height='65%' ></iframe>
		</div>
		<div id="pazienti" class="tab">
			<iframe id='gestione_pazienti' src="./gestione_pazienti.php" scrolling="yes" frameborder="0" width='100%' height='65%' ></iframe>
		</div>
		<div id="diagnosi" class="tab">
			<iframe id='esegui_diagnosi' src="./esegui_diagnosi.php"  scrolling="yes" frameborder="0" width='100%' height='65%' ></iframe>
		</div>
		<div id="logout" class="tab">
		</div>
	</body>
</html>