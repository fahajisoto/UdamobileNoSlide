<?php
include_once('../restaurant/config.php');

/*recupe les plats des restos pour les mettre dans le tableau [menu][$serv]*/
function menu($result,$serv)
{
	$tab = explode(';', $result);
	$menu=array();
	if($tab[0]=="ok")
	{
	  if($tab[1]>0)
		{

		 foreach (array_slice($tab, 2) as $element)
		  {
			switch ($element) {
				case "e":
						$k="Entrées";
						break;
						
				case "p":
				
						$k="Plats";
						break;
					
				case "l":
						$k="Légumes";
						break;
					
				case "d":
						$k="Desserts";
						break;
				default:
					if(isset($k))
					{
						if(!isset($menu[$k][0]))
						{
							$menu[$k]=array();
						}
						$menu[$k][]=$element;
						
					}
					break;
			}
		  }
		}
	}
	return $menu;
}

/*fonction qui appel le web service des menus pour le midi et le soir*/
function getMenu($id)
{

	$client = new SoapClient(null, array('location' => CONFIG::urlWSCrous,  'uri' => '','proxy_host' => CONFIG::proxyCRRI,
										'proxy_port'     => CONFIG::proxyPort));

	try {
		$resultM = $client->__soapCall('menu_jour', array('id' => $id, 'code-service' =>'m'));
		$resultS = $client->__soapCall('menu_jour', array('id' => $id, 'code-service' =>'s'));
		$mM=menu($resultM,'m');
		$mS=menu($resultS,'s');
		//si les menus sont vide
		if(sizeof($mM)==0 && sizeof($mS)==0)
			{$retour = null;}
		else
			{
			$retour = array();
			$retour["midi"]=$mM;
			$retour["soir"]=$mS;
			}
	 //print $retour;
	 return $retour;

	}
	catch (SoapFault $fault) 
	{
	print $fault->getMessage();
	}
}
//recup la liste des resto
function getListeResto()
{
	$time=date('Y-m-d');
	
	$client = new SoapClient(null, array('location' => CONFIG::urlWSCrous,  'uri' => '','proxy_host' => CONFIG::proxyCRRI,
										'proxy_port'     => CONFIG::proxyPort));

	try {

		$result = $client->__soapCall('liste_restos', array());
		$tab = explode(';', $result);
		$retour = array();
		$retour["date_retour"]=$time;
		
		//WS ok on coupe de fichier csv		
		if($tab[0]=="ok")
		{
			$retour["code_retour"]="ok";
			$n=$tab[1];
			for($i=0;$i<$n;$i++)
			{
				$retour[$tab[$i*8+2]]=array();
				for($j=0;$j<3;$j++)
				{
				$retour[$tab[$i*8+2]][$j]=$tab[$i*8+3+$j];
				}
			}
		}
		else
			{
				$retour["code_retour"]="erreur";
			}
		return $retour;
	}
	catch (SoapFault $fault) 
	{
	print $fault->getMessage();
	}
}
//construction du tab json avec le resto et menu
function getTabJson()
{

	$listeR=getListeResto();

		$id=array();
		$id=array_keys($listeR);
		$tabJson=array();
		$tabJson=$listeR;
		
		
		
		foreach (array_slice($id , 2)as $element)
		{
			$tabJson[$element]["menu"]="";
			 $eltM=getMenu($element);
			 if($eltM)
			 {
				$tabJson[$element]["menu"]=$eltM;
			}
			 else
			 {
				unset($tabJson[$element]);
			}
		}
		return(json_encode($tabJson));
		//print_r($listeR);
		
}
 ?>
