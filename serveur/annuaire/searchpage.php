<?php 
require_once("../restaurant/include.php");
if(isset($_REQUEST["name"])) {
	$name=trim($_REQUEST["name"]);
	
	if(strlen($name)>1) {
		$ds=ldap_connect(CONFIG::LDAP_URL);
		if (!$ds) print ("Connection LDAP impossible\n");
			   
		$sr=ldap_search($ds, CONFIG::LDAP_BASEDN,'(&(CLFDstatus=9)(sn='.$name.'*))',array('sn','givenName','mail','telephoneNumber'));		
		$infos = ldap_get_entries($ds, $sr);
		$nbrP=$infos["count"];

		$annuaire=array();
		$annuaire['count']=$nbrP;
		
		for($i=0;$i<$nbrP;$i++) {
			$annuaire[$i]['nom']=$infos[$i]['sn'][0];
			$annuaire[$i]['prenom']=$infos[$i]['givenname'][0];
			$annuaire[$i]['mail']=$infos[$i]['mail'][0];
			if(isset($infos[$i]['telephonenumber'][0])) $annuaire[$i]['tel']=$infos[$i]['telephonenumber'][0];
		}
		
		print(json_encode($annuaire));
	}
}
?>