<?php
require_once("fctMenu.php");
$menu=getTabJson();

$fp = fopen('menus.txt','w');
fwrite($fp, $menu);
fclose($fp);

$tab=json_decode($menu,true);
$time=date('Y-m-d H:i:s');
if ($tab["code_retour"]=="ok" && $tab["date_retour"]==date('Y-m-d'))
	print $time." : ok;\n";
else
	print $time." : ko;\n";
?>