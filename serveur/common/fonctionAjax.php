<?php
//gestion des accents
header('Content-type: text/html; charset=UTF-8');
require_once("include.php");

//recuperation des param
$fct=$_REQUEST["fct"];
if(isset($_REQUEST["latitude"])&&isset($_REQUEST["longitude"]))
{
	$latitude=$_REQUEST["latitude"];
	$longitude=$_REQUEST["longitude"];
}

//traitement pour les restos et menus
$handle = fopen('../restaurant/menus.txt', "r");
$content= fread($handle, filesize('../restaurant/menus.txt'));
fclose($handle);
$menu=json_decode($content,true);

//recup de l'id du resto pour le menu
if(isset($_REQUEST["resto"]))
{
	$resto=$_REQUEST["resto"];
}
//differentes fonctions
switch ($fct) {

	//fct d'initialisation
	case "start":
			{
				$obj=json_decode(file_get_contents(CONFIG::POI),true);
				//Calcul des distances entre l'utilisateur est les POI
				//print_r($obj);
				$nType=sizeof($obj["Types"]["tab"]);
				$TabOrdre=array();
				$nb=0;
				//tous les types
				for($i=1;$i<$nType;$i++)
				{
					$n=sizeof($obj[$obj["Types"]["tab"][$i]]);
					//tous les elements des types
					for($j=0;$j<$n;$j++)
					{
						//calcul de la distance avec arrondi 1 chiffre apres la virgule
						$d=round(get_distance_m($latitude, $longitude, $obj[$obj["Types"]["tab"][$i]][$j]["latitude"], $obj[$obj["Types"]["tab"][$i]][$j]["longitude"]) / 1000, 1);
						$id=$obj[$obj["Types"]["tab"][$i]][$j]["id"];
						//mise en place du tableau ordre
						$TabOrdre[$id]=$d;
						$nb++;
					}

					
				}
				//Tri du tableau du + au - proche 
				$result =asort($TabOrdre);			
				$retour = array();
				$retour['ordreDist']=$TabOrdre;
				$retour['objPOI']=$obj;
				print(json_encode($retour));
				break;
			}
	
	//calcul des nouvelles distances
	case "tri":
			{
				$obj=json_decode(file_get_contents(CONFIG::POI),true);
				//Calcul des distances entre l'utilisateur est les POI
				//print_r($obj);
				$nType=sizeof($obj["Types"]["tab"]);
				$TabOrdre=array();
				$nb=0;
				for($i=1;$i<$nType;$i++)
				{
					$n=sizeof($obj[$obj["Types"]["tab"][$i]]);
					for($j=0;$j<$n;$j++)
					{
						//calcul de la distance avec arrondi 1 chiffre apres la virgule
						$d=round(get_distance_m($latitude, $longitude, $obj[$obj["Types"]["tab"][$i]][$j]["latitude"], $obj[$obj["Types"]["tab"][$i]][$j]["longitude"]) / 1000, 1);
						$id=$obj[$obj["Types"]["tab"][$i]][$j]["id"];
						$TabOrdre[$id]=$d;
						$nb++;
					}
				}
				//Tri du tableau du + au - proche 
				$result =asort($TabOrdre);			
				$retour = array();
				$retour['ordreDist']=$TabOrdre;
				print(json_encode($retour));
				break;
			
			}
	
	//fonction liste resto alpha
	case "menuAlpha":
			{
				$i=0;
				if($menu["code_retour"]=="ok" &&  $menu["date_retour"]==date('Y-m-d'))
				{
					$TabResto=array();
					$TabResto["resto"]=array();
					$id=array();
					$id=array_keys($menu);				
					$TabResto["resto"][$i]["code_retour"]="ok";
					$i++;
					foreach (array_slice($id , 2)as $element)
						{
							 $TabResto["resto"][$i]["nom"]=$menu[$element][0];
							 $TabResto["resto"][$i]["code_restaurant"]=$element;						 
							 $i++;
						}
				}
				else
					$TabResto["resto"][$i]["code_retour"]="ko";
				//mise en place du tableau par ordre alphabetique 
				$result =sort($TabResto["resto"]);
				print(json_encode($TabResto["resto"]));
				break;			
			}
	
	//fct liste resto geographique
	case "menuGeo":
		{
			$i=1;
			if($menu["code_retour"]=="ok" &&  $menu["date_retour"]==date('Y-m-d'))
			{
				$TabResto=array();
				$TabResto["resto"]=array();
				$id=array();
				$id=array_keys($menu);				
				foreach (array_slice($id , 2)as $element)
					{
						 $d=round(get_distance_m($latitude, $longitude, $menu[$element][1],$menu[$element][2]) / 1000, 1);
						 $TabResto["resto"][$i]["nom"]=$menu[$element][0];
						 $TabResto["resto"][$i]["code_restaurant"]=$element;
						 $TabResto["resto"][$i]["distance"]=$d;
						 $i++;
					}
			}
			else
				$TabResto["resto"][$i]["code_retour"]="ko";
			$result =sort_by_key($TabResto["resto"],"distance");
			$TabResto["resto"][0]["code_retour"]="ok";
			$return=array();
			foreach($TabResto["resto"] as $elt)
			{
				$return[]=$elt;
			}
			print(json_encode($return));
			break;
		}
	
	//fonction menu par resto
	case "menu":
		{
			//si on a pas rencontré d'erreur dans les WS on construit le html du menu
			if($menu["code_retour"]=="ok" &&  $menu["date_retour"]==date('Y-m-d'))
			{
				$carte=	'<input id="latR" type="hidden" value="'.$menu[$resto][1].'"/><input id="longR" type="hidden" value="'.$menu[$resto][2].'"/>';
				$carte.= "<li rel=".$resto."><p class='service'><span style='text-decoration:underline' >Midi</span> :</p><h1>Menu</h1><div class='menu'>".menu($menu[$resto]['menu']['midi'])."</div></li>";
				if(sizeof($menu[$resto]['menu']['soir'])>0)
					$carte.= "<li rel=".$resto."><p class='service'>Soir :</p><h1>Menu</h1><div class='menu'>".menu($menu[$resto]['menu']['soir'])."</div></li>";
				else
					$carte.= "<li rel=".$resto."><p class='service'><span style='text-decoration:underline' >Soir</span> : Pas de service</p></li>";
				
			}
			else //sinon on a rencontre un prob donc on affiche rien pour ne pas mettre des menus de la veille ou autre
			{
				$carte= "<li rel=".$resto."><p class='service'> Service temporairement indisponible </p></li>";
			}
			print $carte;
			break;
		}

}
?>