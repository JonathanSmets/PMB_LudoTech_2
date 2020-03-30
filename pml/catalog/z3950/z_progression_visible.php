<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: z_progression_visible.php,v 1.11.4.1 2015-11-22 18:02:00 Alexandre Exp $

// définition du minimum nécéssaire
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "";
//permet d'appliquer le style de l'onglet ou apparait la frame
$current_alert = "catalog";
require_once ("$base_path/includes/init.inc.php");

// les requis par z_progression_visible.php ou ses sous modules
require_once("$include_path/isbn.inc.php");
require_once("$include_path/marc_tables/$pmb_indexation_lang/empty_words");
require_once("$class_path/iso2709.class.php");
require_once("z3950_func.inc.php");
//print "<div id='contenu-frame'>";

print "
<div id='contenu-frame'>
<h1>$msg[z3950_progr_rech]</h1>
<!--
<br /><p align='center'>$msg[z3950_progr_rech_txt]</p>
-->
<table class='nobrd' width='100%' align='center'>";

print "
	<div id='zframe1' align='center'> ".$msg['patientez']."
		<div id='joke' style='visibility:\"visible\";'><img src='../../images/patience.gif'></div>
	</div>";

//
// On détermine les Bibliothèques sélectionnées
//

$recherche=mysql_query("select * from z_ludotech $selection_bib");
$parity = 1;
while ($resultat=pmb_mysql_fetch_array($recherche)) {
	$bib_id=$resultat["id_ludotech"];
	$nom_bib=$resultat["libelle_ludotech"];
	if ($parity % 2) {
		$pair_impair = "even";
		} else {
			$pair_impair = "odd";
			}
	$parity += 1;

	print "
		<tr class='$pair_impair'>
			<td width='30%'>$nom_bib</td>
			<td><div id='z$bib_id'>$msg[z3950_essai_cnx]</div></td>
		</tr>";
	}

print "
</table></div>";
?>
