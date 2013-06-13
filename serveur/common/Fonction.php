<?php

// renvoi la distance en mètres
function get_distance_m($lat1, $lng1, $lat2, $lng2) {
  $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
  $rlo1 = deg2rad($lng1);
  $rla1 = deg2rad($lat1);
  $rlo2 = deg2rad($lng2);
  $rla2 = deg2rad($lat2);
  $dlo = ($rlo2 - $rlo1) / 2;
  $dla = ($rla2 - $rla1) / 2;
  $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
  $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
  return ($earth_radius * $d);
}

//Fonction pour trier selon les distances les POI
function sort_by_key($array, $index) {
	$sort = array();

	//préparation d'un nouveau tableau basé sur la clé à trier
	foreach ($array as $key => $val) {
		$sort[$key] = $val[$index];
	}

	//tri par ordre naturel et insensible à la casse
	natcasesort($sort);

	//formation du nouveau tableau trié selon la clé
	$output = array();
	foreach($sort as $key => $val) {
		$output[] = $array[$key];
	}
return $output;
}

function menu($menu)
{
	$retour="";
	foreach(array_keys($menu) as $element)
		{
			//print($element);
			$retour.="<h3>".$element."</h3>";
			foreach($menu[$element] as $pla)
				{
					$retour.="<p>".$pla."</p>";
				}
		}
	return $retour;
}

?>

