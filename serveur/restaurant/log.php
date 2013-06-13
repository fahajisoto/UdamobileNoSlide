<?php 
session_start();
if(isset($_SESSION['id']))
	{
		//fonction pour les statistiques
		$time=date('Y-m-d H:i:s');
		//param retenus la date, l'id et la fonction
		$fct="/resto/";	
		$id=$_SESSION['id'];
		$data=$id.";".$time.";".$fct.";";
		//ecriture fichier log
		$fp = fopen('log.txt','a');
		fwrite($fp,$data);
		fclose($fp);
	}
?>