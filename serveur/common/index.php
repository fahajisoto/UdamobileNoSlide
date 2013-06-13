<?php 
session_start();
if(isset($_SESSION['id']))
	{
		//fonction pour les statistiques
		
		$time=date('Y-m-d H:i:s');
		//param retenus la date, l'id et la fonction
		$fct="/annuaire/";	
		$id=$_SESSION['id'];
		$data=$id.";".$time.";".$fct.";";
		//ecriture fichier log
		$fp = fopen('./log.txt','a');
		fwrite($fp,$data);
		fclose($fp);
	}
	
?>
<?php
require_once("lib/include.php");
	if(isset($_POST["name"]))
		{
			$name=trim($_POST["name"]);
			if(strlen($name)>1)
			{
				$ds=ldap_connect(CONFIG::LDAP_URL);
				   if (!$ds) 
				   print ("Connection LDAP impossible\n");
				   
				   $sr=ldap_search($ds, CONFIG::LDAP_BASEDN,'(&(CLFDstatus=9)(sn='.$name.'*))',array('sn','givenName','mail','telephoneNumber'));
				   $infos = ldap_get_entries($ds, $sr);
				   $nbrP=$infos["count"];
				   $annuaire=array();
				   $annuaire['count']=$nbrP;
				   for($i=0;$i<$nbrP;$i++)
					{
						$annuaire[$i]['nom']=$infos[$i]['sn'][0];
						$annuaire[$i]['prenom']=$infos[$i]['givenname'][0];
						$annuaire[$i]['mail']=$infos[$i]['mail'][0];
						if(isset($infos[$i]['telephonenumber'][0]))
							$annuaire[$i]['tel']=$infos[$i]['telephonenumber'][0];
						
					}
					$json=json_encode($annuaire);
			}
		}

?>
<html >
<head>
</head>

	<meta name="viewport" content="initial-scale=1.0,user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title>Annuaire - Rechercher une personne</title>
	<script type="text/javascript" src="../common/js/jquery.min.js"></script>
	<script type="text/javascript" src="../common/js/jquery.json.js"></script>
	<script type="text/javascript" src="jsAnnuaire.js"></script>
	
<style type="text/css">	
body{font-family:Helvetica,Arial,sans-serif;margin:0}h1{height:1.8em;font-size:1.7em;margin-top:10px;border-bottom:2px dashed #111}h2{font-size:1.4em;font-weight:bold}
form{margin:1em;font-size:1.2em}
#submit{ height:40px; width: 45px; background: url('../images/search.png') #e1e1e1 no-repeat 5 0; border-style:none;border-left:solid 1px;display:block;float:left;border-radius:0px;padding:0px}
form div{border: 1px solid; height: 40px; width: 245px;margin-top: 5px;}
#name{height:40px;width: 200px; font-size: 1.3em;float:left;border-style:none;}
#annu{display:none}.list{background:none repeat scroll 0 0 white;display:block;padding-left:.5em}
.ElementListe{border-bottom:1px solid #ccc;clear:both;display:block;min-height:3em;padding:8px 0}p{font-size:1em}a{color:#7AAD00}
<?php echo file_get_contents("../CSS/Navbar.css")?>
</style>	
<body>
<div id="Navbar">
		<a id="home" href="http://udamobile.u-clermont1.fr/"><img src="../map/weblib/images/Icon/home.png" ></a>
</div>
<h1>Rechercher un contact</h1>
<form action="index.php" method="post">
	Nom du contact :
	<div>
		<input type="text" name="name" id="name"/>
		<input type="submit" value="" id="submit"/>
	</div>
</form>
<div id="annu"><?php if(isset($json)) echo($json);?></div>
<ul id="listAnnu" class="list"></ul>
</body>
</html>