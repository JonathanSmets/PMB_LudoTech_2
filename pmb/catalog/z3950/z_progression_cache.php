<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : Marco VANINETTI                                                           |
// +-------------------------------------------------+
// $Id: z_progression_cache.php,v 1.36.4.1 2015-11-22 18:01:05 Alexandre Exp $

// dÈfinition du minimum nÈcÈssaire
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "";
$base_nobody = 1;
require_once ("$base_path/includes/init.inc.php");

// les requis par z_progression_main.php ou ses sous modules
require_once ("$include_path/isbn.inc.php");
require_once ("$include_path/marc_tables/$pmb_indexation_lang/empty_words");
require_once ("$class_path/iso2709.class.php");
require_once ("z3950_func.inc.php");
require ('notice.inc.php');
// new for decoding record sutrs
require_once ("z3950_sutrs.inc.php");
include("$class_path/z3950_notice.class.php");
require_once ("$base_path/includes/db_param_ludotech.php");

//Correction mystÈrieuse : visiblement la fonction yaz_search impacte la variable global $limite... modifiÈ en $limite_notices
$limite_notices+=0;
function critere_isbn ($val1) {
	$val=$val1;
	if(isEAN($val1)) {
		// la saisie est un EAN -> on tente de le formater en ISBN
		$val1 = z_EANtoISBN($val1);
		// si Èchec, on prend l'EAN comme il vient
		if(!$val1) $val1 = $val;
		} else {
			if(isISBN($val1)) {
				// si la saisie est un ISBN
				$val1 = z_formatISBN($val1,13);
				// si Èchec, ISBN erronÈ on le prend sous cette forme
				if(!$val1) $val1 = $val;
				} else {
					// ce n'est rien de tout Áa, on prend la saisie telle quelle
					$val1 = $val;
					}
			}
	return $val1;
	}


$mioframe="frame1";
//affiche_jsscript ("configuring the connections...", "#FFB7B7", $mioframe);

////////////////////////////////////////////////////////////////////
// Fase 1: we prepare the query and the connection fore each biblio
///////////////////////////////////////////////////////////////////

//si puÚ mettere prima del ciclo while principale....?
// Remise ‡ "" de tous les attributs de critËre de recherche
$map=array();

$rqt_bib_attr=pmb_mysql_query("select attr_libelle from z_attr group by attr_libelle ");
while ($linea=pmb_mysql_fetch_array($rqt_bib_attr)) {
	$attr_libelle=$linea["attr_libelle"];
	$var = "attr_".strtolower($attr_libelle) ;
	$$var = "" ;
}

/* (mdarville)
 * recherche des donn√©es de toute les ludotech selectionn√©e
 * $selection_bib contiendra un truc du genre "WHERE id_ludotech IN (-- id des ludotech selectionnee --)
 */
$rq_bib_z3950=mysql_query ("select * from z_ludotech $selection_bib order by libelle_ludotech, id_ludotech ");
/* (mdarville)
 * fetch sur les ludotech selectionn√©es pour reprendre leur donn√©es (necessaire pour notre connexions)
 * il faudre surement ensuite faire des connexions pour aller chercher nos donn√©es sur les autres DB
 */

$ind = 0;
$t1=time();
while ($ligne=pmb_mysql_fetch_array($rq_bib_z3950)) {
    	/*
    	$bib_id=$ligne["bib_id"];
		$url=$ligne["url"];
		$port=$ligne["port"];
		$base=$ligne["base"];
		$format=$ligne["format"];
		$auth_user=$ligne["auth_user"];
		$auth_pass=$ligne["auth_pass"];
		$sutrs_lang=$ligne["sutrs_lang"];
		$auth=$auth_user.$auth_pass;
		$formato[$bib_id]=$format;
     * 
     */
     /* (mdarville)
      * Stockage des donn√©es d'acces aux ludotech.
      */
        $tab_ludotech[$ind] = $ligne["id_ludotech"];
        $tab_ludotech_name[$ind] = $ligne["libelle_ludotech"];
        $tab_ludotech_count[$ind] = 0;
        $tab_ludotech_error[$ind] = 0;
        $bib_id = $ligne["id_ludotech"];
        $libelle_ludotech = $ligne["libelle_ludotech"];
        $nameDB_ludotech = $ligne["nameDB_ludotech"];
        $ip_ludotech = $ligne["ip_ludotech"];
        $user_ludotech = $ligne["user_ludotech"];
        $pwd_ludotech = $ligne["pwd_ludotech"];
      /* (mdarville)
       * il faudra, une fois que la requete sera cr√©e, faire le connexion √† la
       * db distante.
       */

	// chargement des attributs de la bib sÈlectionnÈe
	$rqt_bib_attr=pmb_mysql_query("select * from z_attr where attr_bib_id='$bib_id'");
	while ($linea=pmb_mysql_fetch_array($rqt_bib_attr)) {
		$attr_libelle=$linea["attr_libelle"];
		$attr_attr=$linea["attr_attr"];
		$var = "attr_".strtolower($attr_libelle) ;
		$$var = $attr_attr ;
	}

	// On dÔøΩtermine la requÔøΩte ÔøΩ envoyer
    /* (mdarville)
     * il faudra cr√©er notre requetes sql ici
     * cette requete ira interroger les BD en fontions des crit√®res que l'on a ins√©r√©
     */
	$booleen="";
	$critere1="";
	$critere2="";
	$troncature="";
    if(val1 != "")
    {
        switch ($crit1){
            case "tit1" :
                $tables = " FROM notices";
                $condition = " WHERE tit1 like '%$val1%' ";
                break;
            case "tit2" :
                $tables = " FROM notices";
                $condition = " WHERE tit2 like '%$val1%' ";
                break;
            default :
                break;
        }
    }

    /*
	if ($bool1 == "ET") $booleen="@and ";
	elseif ($bool1 == "OU") $booleen="@or ";
	elseif ($bool1 == "SAUF") $booleen="@not ";
	
	switch ($crit1) {
		case "titre" :
			$critere1=$attr_titre;
			break;
		case "mots" :
			$critere1=$attr_mots;
			break;
		case "resume" :
			$critere1=$attr_resume;
			break;
		case "type_doc" :
			$critere1=$attr_type_doc;
			break;
		case "auteur" :
			$critere1=$attr_auteur;
			break;
		case "sujet" :
			$critere1=$attr_sujet;
			break;
		case "isbn" :
			$critere1=$attr_isbn;
			//$val1=critere_isbn($val1);
			break;
		case "issn" :
			$critere1=$attr_issn;
			break;
		case "isrn" :
			$critere1=$attr_isrn;
			break;
		case "ismn" :
			$critere1=$attr_ismn;
			break;
		case "mk" :
			$critere1=$attr_mk;
			break;
		case "cbsonores" :
			$critere1=$attr_cbsonores;
			break;
		case "ean" :
			$critere1=$attr_ean;
			break;
		case "allfields" :
			$critere1=$attr_allfields;
			break;
		default :
			break;
		}
	
	switch ($crit2) {
		case "titre" :
			$critere2=$attr_titre;
			break;
		case "mots" :
			$critere1=$attr_mots;
			break;
		case "resume" :
			$critere1=$attr_resume;
			break;
		case "type_doc" :
			$critere1=$attr_type_doc;
			break;
		case "auteur" :
			$critere2=$attr_auteur;
			break;
		case "sujet" :
			$critere2=$attr_sujet;
			break;
		case "isbn" :
			$critere2=$attr_isbn;
			//$val2=critere_isbn($val2);
			break;
		case "issn" :
			$critere2=$attr_issn;
			break;
		case "isrn" :
			$critere2=$attr_isrn;
			break;
		case "ismn" :
			$critere2=$attr_ismn;
			break;
		case "mk" :
			$critere2=$attr_mk;
			break;
		case "cbsonores" :
			$critere2=$attr_cbsonores;
			break;
		case "ean" :
			$critere2=$attr_ean;
			break;
		case "allfields" :
			$critere2=$attr_allfields;
			break;
		default :
			break;
		}

	$term="";
	
	if ($val1 != "" AND $val2 == "" AND $critere1 != "" ) {
		$term="@attr 1=$critere1 @attr 4=1 \"$val1$troncature\" ";
		} 
	if ($val1 == "" AND $val2 != "" AND $critere2 != "" ) {
		$term="@attr 1=$critere2 @attr 4=1 \"$val2$troncature\" ";
		} 
	if ($val1 != "" AND $val2 != "" AND $critere1 != "" AND $critere2 != "" ) {
		$term="$booleen @attr 1=$critere1 @attr 4=1 \"$val1$troncature\"  @attr 1=$critere2 @attr 4=1 \"$val2$troncature\" ";
		}

	if ($term == "") {
		//$stato[$bib_id]=0;
		//$map[$bib_id] = 0;
		if ($val1 == "" AND $val2 == "") {
			affiche_jsscript ($msg[z3950_echec_no_champ], "#FF3333", $bib_id);
		} else {
			affiche_jsscript ($msg[z3950_echec_no_valid_attr], "#FF3333", $bib_id);
		}
	} else {
        
		//////////////////////////////////////////////////////////////////////////////////
		// the query is ok we prepare the Z 3950 process for this biblio and
		// save the $id to be able later to retrieve the records from the servers
		//////////////////////////////////////////////////////////////////////////////////
	
		//$stato[$bib_id] = 1;
		$auth = $auth_user.$auth_pass ;
		if ($auth != "") {
			$id = yaz_connect("$url:$port/$base", array("user" => $auth_user, "password" => $auth_pass, "piggyback"=>false)) or affiche_jsscript ("Echec : impossible de se connecter au Serveur", "#FF3333", $bib_id);
		} else {
			$id = yaz_connect("$url:$port/$base", array("piggyback"=>false)) or affiche_jsscript ($msg[z3950_echec_cnx], "#FF3333", $bib_id);
		}
		$map[$bib_id] = $id;
		yaz_element($id,"F");
		yaz_range ($id, 1, $limite);
		yaz_syntax($id,strtolower($format));
		echo $term;
		yaz_search($id,"rpn",$term);
	}
     *
     */

    if(db_ludotech_connect($nameDB_ludotech, $user_ludotech, $pwd_ludotech, $ip_ludotech) == 0) {
    
    	affiche_jsscript ("Echec : impossible de se connecter au Serveur : ".mysql_error(), "#FF3333", $bib_id);
    	$tab_ludotech_error[$ind] = 1;
    }
    else
    {
    	$sql_ludotech = "select count(*) as total ".$tables.$condition;
    
    	$query_ludotech = mysql_query($sql_ludotech) or ($tab_ludotech_error[$ind] = 1);
    
    
    	if($tab_ludotech_error[$ind] != 1)
    	{
    		$ligne_ludotech = mysql_fetch_array($query_ludotech) or ($tab_ludotech_error[$ind] = 1);
    		$tab_ludotech_count[$ind] = $ligne_ludotech["total"];
    
    		if($tab_ludotech_error[$ind] != 1)
    		{
    			$limite = 100;
    			$sql_ludotech = "select tit1,tit2, notice_id, ed_name ".$tables." LEFT OUTER JOIN publishers ON ed1_id = ed_id".$condition."ORDER BY tit1 LIMIT 0,$limite";
    
    			$query_ludotech = mysql_query($sql_ludotech) or ($tab_ludotech_error[$ind] = 1);
    			if($tab_ludotech_error[$ind] == 1)
    				affiche_jsscript ("Echec : impossible d'ex&eacute;cuter la requ&ecirc;te ".mysql_error(), "#FF3333", $bib_id);
    			$ind_ludotech = 0;
    			while ($line_ludotech=mysql_fetch_array($query_ludotech)) {
    				$ludotech_data[$bib_id][$ind_ludotech]["notice_id"] = $line_ludotech["notice_id"];
    				$ludotech_data[$bib_id][$ind_ludotech]["ludotech_id"] = $bib_id;
    				$ludotech_data[$bib_id][$ind_ludotech]["z_tit1"] = $line_ludotech["tit1"];
    				$ludotech_data[$bib_id][$ind_ludotech]["z_tit2"] = $line_ludotech["tit2"];
    				if(!empty ($line_ludotech["ed_name"]))
    					$ludotech_data[$bib_id][$ind_ludotech]["publisher"] = $line_ludotech["ed_name"];
    				else
    					$ludotech_data[$bib_id][$ind_ludotech]["publisher"] = null;
    
    				$ind_ludotech++;
    			}
    		}
    	}
    
    }
    
    db_ludotech_close();
    
    /* (mdarville)
     * mettre dans un tableau dont l'indice principal serait la ludotech (permettra de faire le count par la suite)
     * le tableau permettre d'ins√©rer apr√®s dans la nouvelle table z_notice.
    */
    $ind++;
}


/* (mdarville)
 * affichage de la zone "TERMINE ..." avec le nombre de cas repris et le nombre total.
 * on skip cette affichage s'il y a d√©j√† eu un affichage d'une erreur au pr√©allable.
 */
$dbh = connection_mysql();
$ind = 0;
foreach ($tab_ludotech as $ludo_id) {
	$hits = count($ludotech_data[$ludo_id]);
	/* (mdarville)
	 * insertion dans z_ludotech_notices des informations n√©cessaires pour
	 * la construction du r√©sum√©.
	*/
	for($ind_ludotech = 0; $ind_ludotech < $hits; $ind_ludotech++)
	{
	if (!empty ($ludotech_data[$ludo_id][$ind_ludotech]["publisher"]))
			$summary = $ludotech_data[$ludo_id][$ind_ludotech]["publisher"]. "  -  ".$tab_ludotech_name[$ind];
			else
				$summary = $tab_ludotech_name[$ind];
				$z_notices_id = $ludotech_data[$ludo_id][$ind_ludotech]["notice_id"];
				$z_tit1 = $ludotech_data[$ludo_id][$ind_ludotech]["z_tit1"];
				$z_tit2 = $ludotech_data[$ludo_id][$ind_ludotech]["z_tit2"];

				$sql_ludotech = "INSERT INTO z_ludotech_notices (id_ludotech, z_notices_id, summary, z_tit1, z_tit2,z_last_query) VALUES ('$ludo_id','$z_notices_id','".addslashes($summary)."','".addslashes($z_tit1)."','".addslashes($z_tit2)."','$last_query_id')";
						$query_ludotech = mysql_query($sql_ludotech) or ($tab_ludotech_error[$ind] = 1);
						if($tab_ludotech_error[$ind] == 1)
							//affiche_jsscript ("Echec lors de l'insertion : ".mysql_error(), "#FF3333", $ludo_id);
							affiche_jsscript ("Echec lors de l'insertion : ".mysql_error()."#FF3333", $ludo_id);

    }
    $msg1 = str_replace ("!!total!!", $hits, $msg[z3950_recup_fini]) ;
    $msg1 = str_replace ("!!hits!!", $tab_ludotech_count[$ind], $msg1) ;
    if($tab_ludotech_error[$ind] == 0)
    	affiche_jsscript ($msg1, "#FFFFCC", $ludo_id);
    	$ind++;

}

///////////////////////////////////////////////////////////////////////////
// Fase 2: all the possible connections are ready now start the researches
//////////////////////////////////////////////////////////////////////////
//Correction mystÈrieuse : visiblement la fonction yaz_search impacte la variable global $limite... modifiÈ en $limite_notices

/*
affiche_jsscript ($msg['z3950_zmsg_wait'], "", $mioframe);

$options=array("timeout"=>45);
$t1=time();

//Override le timeout du serveur mysql, pour Ítre s˚r que le socket dure assez longtemps pour aller jusqu'aux ajouts des rÈsultats dans la base.
$sql = "set wait_timeout = 120";
pmb_mysql_query($sql);

yaz_wait($options);
$dt=time()-$t1;
$msgz=str_replace('!!time!!',$dt,$msg['z3950_zmsg_endw']);
hideJoke();
affiche_jsscript ($msgz, "", $mioframe);
showButRes();
*/

////////////////////////////////////////////////////////////////////
// Fase 3: Now get the results from the biblios
// obviously if the query was ok and there weren't errors
///////////////////////////////////////////////////////////////////
/*
while (list($bib_id,$id)=each($map)){
		$error = yaz_error($id);
		$error_info = yaz_addinfo($id);
		if (!empty($error)) {
			$msg1 = $msg[z3950_echec_rech]." : ".$error.", ". $error_info;
			affiche_jsscript ($msg1, "z3950_failed", $bib_id);
			yaz_close ($id);
		} else {
			$hits = yaz_hits($id);
			$hits+=0;
			if ($hits>$limite_notices) {
				$lim_recherche=$limite_notices;
				$msg1 = str_replace ("!!limite!!", $limite_notices, $msg[z3950_recup_encours]) ;
				$msg1 = str_replace ("!!hits!!", $hits, $msg1) ;
				affiche_jsscript ($msg1, "", $bib_id);
			} else {
				$lim_recherche=$hits;
				$msg1= str_replace ("!!hits!!", $hits, $msg[z3950_recup]) ;
				affiche_jsscript ($msg1, "", $bib_id);
			}
			$total=0;
			for ($p = 1; $p <= $lim_recherche; $p++) {

				$rec = yaz_record($id,$p,"raw");

				// DEBUG
				global $z3950_debug ;
 				if ($z3950_debug) {
 					$fp = fopen ("../../temp/raw".rand().".marc","wb");
	 				fwrite ($fp, $rec);
	 				fclose ($fp);
 					}
				if (strpos($rec,chr(0x1d))!==false)
 					$rec=substr($rec,0,strpos($rec,chr(0x1d))+1);
				$monEnr = new iso2709_record($rec);
				if($monEnr->valid()) {
					$messageframe = " $p ".$msg['z3950_lu_bin'];
					$pb = 0;
				} else {
					$rec = yaz_record($id,$p,"string");
					$monEnr2 = new iso2709_record($rec);
					if ($monEnr2->valid()) {
						$messageframe = "$p ".$msg['z3950_lu_cok'];
						$pb = 0;
					} else {
						// DEBUG
						//$fp = fopen ("../../temp/raw".rand().".sutrs","wb");
						//fwrite ($fp, $rec);
						//fclose ($fp);
						$rec = sutrs_record($rec,$sutrs_lang);
						$messageframe = " $p ".$msg['z3950_lu_chs'];
						//$pb = 1;
						//$rec="";
					}
				}
				if ($pb) $messageframe=$msg["z3950_reception_notice"].$messageframe;
					else $messageframe=$msg["z3950_reception_notice"].$messageframe;

				affiche_jsscript ($messageframe, "", $bib_id);

				if ($rec != "") {
					$total++;
					//if ($total % 10 == 0) {
					//   affiche_jsscript ($msg["z3950_reception_notice"]." $total / $lim_recherche", "#99FF99", $bib_id);
					//}
					$notice = new z3950_notice ($formato[$bib_id], $rec);
					$isbd_affichage = $notice->get_isbd_display ();

					$lu_isbn = $isbd_affichage[0];
					$lu_titre = $isbd_affichage[1];
					$lu_auteur = $isbd_affichage[2];
					$lu_isbd = $isbd_affichage[3];

					$sql2="insert into z_notices (znotices_id, znotices_query_id, znotices_bib_id, isbn, titre, auteur, isbd, z_marc) ";
					$sql2.="values(0,'$last_query_id', '$bib_id', '$lu_isbn', '".addslashes($lu_titre)."', '".addslashes($lu_auteur)."', '".addslashes($lu_isbd)."','".addslashes($rec)."') ";
					pmb_mysql_query($sql2);
					$ID_notice = pmb_mysql_insert_id();
				} // fin du if qui vÈrifie que la notice n'est pas vide
			} // fin for
			yaz_close ($id);
			$msg1 = str_replace ("!!total!!", $total, $msg[z3950_recup_fini]) ;
			$msg1 = str_replace ("!!hits!!", $hits, $msg1) ;
			affiche_jsscript ($msg1, "z3950_succeed", $bib_id);
		} // fin if else error
}
*/
				
$dt=time()-$t1;
$msg1=str_replace('!!time!!',$dt,$msg['z3950_zmsg_show']);
affiche_jsscript ($msg1, "", $mioframe);
hideJoke();
showButRes();
?>
</body>
</html>