<?php

class CONFIG{

	// URL de l'application
	const URL = 'http://192.168.100.175/udamobile/map';

	//tableaux json des POI
	//const POI ='http://192.168.100.175/udamobile/map/weblib/POIS/POI.json';

	//tableau des categories à l'initialisation
	const INIT='{"tab":["Geolocalisation","Université","Restauration"]}';

	//tableau json avec les icons
	const ICONS = '{
		"Université" : "weblib/images/Icon/uda.png",
		"Santé":"weblib/images/Icon/sante1.png",
		"Loisirs":"weblib/images/Icon/loisir1.png",
		"BU":"weblib/images/Icon/BU.png",
		"Restauration":"weblib/images/Icon/RU.png",
		"Hébergement":"weblib/images/Icon/HebergementU.png",
		"Divers":"weblib/images/Icon/divers1.png"
	}';
	
	const LDAP_URL  = 'ldap://unadap.u-clermont1.fr';
	const LDAP_BASEDN = "ou=people,dc=u-clermont1,dc=fr";
	const urlWSCrous = 'http://www.crous-clermont.fr/Webservices/Restos/service.php'; 
	const proxyCRRI = "192.168.122.200";
	const proxyPort = 8080;
	
  }
?>

