<?php

$base_path="..";

require_once ("../includes/init.inc.php");

function getRandomPassword($length = 8) {
	// code modified from http://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
	
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); 
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < $length; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function updatePwdEmpr($emprId, $pwd) {

	$update = " update empr set empr_password = " . addslashes($pwd) . " where id_empr = " . $emprId;	
	return mysql_query($update);

}

function isValueInAList($value, $list, $ignoreCase=true)
// vérifier que la valeur est dans la list
// la list doit être un tableau
{
	if ($ignoreCase)
	{

		$found = false;

		foreach ($list as $element)
		{
			// strcasecmp : comparaison ignoreCase de String : si = 0 les 2 chaines sont égales
			if (strcasecmp($value, $element) == 0)
			{
				$found = true;
				break;
			}
		}

		return $found;

	} else {
		return in_array($value, $list);
	}

}


function IsEmpty($variable)
{
	if (isset($variable) && (strlen($variable)> 0))
		return false;
	return true;

}

function ContentIgnoreCase($string, $needle)
// check if $neelde is a part of $string (non case sensitive)
// $neelde could be an array => check if one value of the array $needle is a part of $string
{
	if (is_array($needle))
	{
		$res = false;
		foreach ($needle as $n)
		{
			$res = stripos($string, $n) !== false;
			if ($res) break; // found !!!
		}
		return $res;
	} else return (stripos($string, $needle) !== false );
}

function EqualsIgnoreCase($string, $toCompare)
// check if two strings are the same (NON case sensitive)
// $toCompare could be an array => check if one value of the array $toCompare is the same than $string
{
	if (is_array($toCompare))
	{
		$res = false;
		foreach ($toCompare as $t){
			$res = strcasecmp($string, $t) == 0;
			if ($res) break; // found
		}
		return $res;
	} else return strcasecmp($string, $toCompare) == 0;
}


function process_date($date) {
	$res = array();
	$res['day'] = "";
	$res['month'] = "";
	$res['year'] = "";
	
	$temp = explode(' ', $date);
	if (count($temp) >= 3) {
		$res['day'] = $temp[0];
		$res['month'] = $temp[1];
		$res['year'] = $temp[2];
	} elseif (count($temp) == 2) {
		$res['day'] = "01";
		$res['month'] = $temp[0];
		$res['year'] = $temp[1];
	} elseif (count($temp) == 1) {
		$res['day'] = "01";
		$res['month'] = "01";
		$res['year'] = $temp[0];
	}
	
	$res['year'] = process_year($res['year']);
	$res['month'] = process_month($res['month']);
	$res['day'] = (strlen($res['day']) == 1 ? '0' : '') . $res['day']; 
	
	return $res;
	
}

function process_year($dateTemp) {
	if (strlen(trim($dateTemp)) == 4 && is_numeric(trim($dateTemp))) {
		// la date est yyyy
		// echo "DEBUG FFI : datetemp yyyy";
		return trim($dateTemp);
	} 
	return 0;

}


function process_month($libelle) {
	// le but est de sortir le mois en numérique
	// echo "DEBUG FFI : process Month : libelle = $libelle <br>";

	$month = "";

	if (stripos($libelle, 'janvier') !== false || stripos($libelle, 'january') !== false || stripos($libelle, 'Genniao') !== false) {
		$month = "01";
	} elseif (stripos($libelle, 'février') !== false || stripos($libelle, 'fevrier') !== false || stripos($libelle, 'february') !== false || stripos($libelle, 'februari') !== false || stripos($libelle, 'febbraio') !== false) {
		$month = "02";
	} elseif (stripos($libelle, 'mars') !== false || stripos($libelle, 'march') !== false || stripos($libelle, 'maart') !== false || stripos($libelle, 'marzo') !== false) {
		$month = "03";
	} elseif (stripos($libelle, 'avril') !== false || stripos($libelle, 'april') !== false || stripos($libelle, 'aprile') !== false) {
		$month = "04";
	} elseif (stripos($libelle, 'mai') !== false || stripos($libelle, 'may') !== false  || stripos($libelle, 'mei') !== false || stripos($libelle, 'maggio') !== false) {
		$month = "05";
	} elseif (stripos($libelle, 'juin') !== false || stripos($libelle, 'june') !== false  || stripos($libelle, 'juni') !== false || stripos($libelle, 'giugno') !== false) {
		$month = "06";
	} elseif (stripos($libelle, 'juillet') !== false || stripos($libelle, 'july') !== false || stripos($libelle, 'juli') !== false || stripos($libelle, 'luglio') !== false) {
		$month = "07";
	} elseif (stripos($libelle, 'août') !== false || stripos($libelle, 'aout') !== false || stripos($libelle, 'august') !== false || stripos($libelle, 'agosto') !== false) {
		$month = "08";
	} elseif (stripos($libelle, 'septembre') !== false || stripos($libelle, 'septemb') !== false || stripos($libelle, 'settembre') !== false) {
		$month = "09";
	} elseif (stripos($libelle, 'octobre') !== false || stripos($libelle, 'oct') !== false || stripos($libelle, 'oktober') !== false || stripos($libelle, 'ottobre') !== false) {
		$month = "10";
	} elseif (stripos($libelle, 'novembre') !== false|| stripos($libelle, 'nov') !== false) {
		$month = "11";
	} elseif (stripos($libelle, 'décembre') !== false || stripos($libelle, 'decembre') !== false || stripos($libelle, 'decem') !== false || stripos($libelle, 'dicembre') !== false) {
		$month = "12";
	} else $month = "01";


	return $month;
}

function processTitle($value, $delimiter) {
	$res = array();
	$res[0] = "";
	$res[1] = "";
	$value = trim($value);
	$temp = explode($delimiter, $value);
	if (count($temp) > 1 ) {
		$res[0] = trim($temp[0]);
		for ($i = 1; $i < count($temp) ; $i++) {
			$res[1] .= trim((strlen($res[1]) > 0 ? " " . $delimiter . " " : "")  . $temp[$i]);
		}
	} else {
		$res[0] = $value;
	}
	return $res;
}

function processTitleSubTitle($value) {
	$res = array();	
	$res_temp = processTitle($value, ":");
	$res['title'] = $res_temp[0];
	$res['subTitle'] = $res_temp[1];
	return $res;
	
}

function processTitleComplementTitle($value) {
	$res = array();
	$res_temp = processTitle($value, "=");
	$res['title'] = $res_temp[0];
	$res['complementTitle'] = $res_temp[1];
	return $res;
}

function reinsertRejectedArticle($value) {
	// regarde si la valeur contient un article en dernière parenthèse
	// si oui on supprime la dernière prenthèse 
	// on remet l'article au début
	
	$returnValue = "";
	
	$temp_coll_ffi = extractContentParenthesis($value);
	$articles_collection = array('le', 'la', 'les', "l'", "un", "du", "des", "mes", "nos", "de la");
	$temp_coll_ffi['parenthesis'] = trim($temp_coll_ffi['parenthesis']);
	
	if (strlen($temp_coll_ffi['parenthesis']) > 0) {
		foreach ($articles_collection as $article) {
			if (strtolower($temp_coll_ffi['parenthesis']) == strtolower($article) ) {
				$returnValue =  $temp_coll_ffi['parenthesis'] . ( $article == "l'" ? '' : ' ') . trim($temp_coll_ffi['content']);
				break;
			}
		}
	}
	
	if (strlen($returnValue) > 0) return firstLetterUpperCaseFirstWord($returnValue);
	else return $value;
	
}

function extractContentParenthesis($value) {
	// parse une chaine de caractère chaine(valeur)
	// retourne un tableau :
	// - content : contenu de la string sans la parenthèse (ici, chaine)
	// - parenthesis : contenu de la parenthese uniquement (ici, valeur)
	
	$res = array();
	$res['content'] = "";
	$res['parenthesis'] = "";
	
	
	$parenthese1 = strrpos($value, '(');
	if ($parenthese1 === false) {
		// rien trouve
		$res['content'] = $value;		
		return $res;
	} else {
		$res['content'] = substr($value, 0, $parenthese1);
		$parenthese1++;
		$parenthese2 = strrpos($value, ')');
		if ($parenthese2 === false) { $parenthese2 = strlen($value); }
	
		$res['parenthesis'] = substr($value, $parenthese1, $parenthese2-$parenthese1);
	
	}
	
	return $res;
	
}

function removeLastChar($value, $charToRemove) {
// function removeLastChar($value) {
	
	$longueur = strlen($value);
	
	if ($longueur > 0) {
		
		if ($value[$longueur-1] == $charToRemove) {
			
			return trim(substr($value, 0, $longueur-1));			
		} else return $value;
		
		
	} else return $value;
	
}


function firstLetterUpperCase($value) {
	
	return ucwords(strtolower($value));
	
}

function firstLetterUpperCaseFirstWord($value) {
	return ucfirst($value); //
}


function importCPExemplaire($value, $titre, $type="text", $dataType="small_text", $isHTML= FALSE) {	
	return importCPGeneric($value, $titre, "expl", $type, $dataType, $isHTML);	
}

function importCPNotice($value, $titre, $type="text", $dataType="small_text", $isHTML= FALSE) {
	return importCPGeneric($value, $titre, "notices", $type, $dataType, $isHTML);
}

function importCPEmprunteur($value, $titre, $type="text", $dataType="small_text", $isHTML= FALSE) {
	return importCPGeneric($value, $titre, "empr", $type, $dataType, $isHTML);
}

function importCPGeneric($value, $titre, $entity="notices", $type="text", $dataType="small_text", $isHTML= FALSE) {
	$value = trim($value);

	if (strlen($value) ==0) return FALSE;

	$nbres = 0;
	$result = true;

	$sql = 'select idchamp from '. $entity . '_custom where name = \''.addslashes($value).'\' or titre = \'' . addslashes($titre) . '\' ';

	$result = mysql_query($sql);
	if ($result) $nbres = mysql_num_rows($result);


	if (!$result) {
		return 0;
	} elseif ($nbres == 0 ) {

		if ($type == "comment")
		{
			$sql2 = "INSERT INTO " . $entity . "_custom (name, titre, type, datatype, options, multiple, obligatoire, ordre, search, export, exclusion_obligatoire, pond, opac_sort) VALUES";
			$sql2 .= " ('" . addslashes(str_replace(' ' , '', clean_string($value))) . "', '" . addslashes($titre) . "', '" . $type .  "', '" . $dataType . "', '<OPTIONS FOR=\"" . $type  . "\">\r\n <COLS>75</COLS>\r\n <ROWS>10</ROWS>\r\n <MAXSIZE>1000</MAXSIZE>\r\n <REPEATABLE>0</REPEATABLE>\r\n <ISHTML>" . ($isHTML ? "1" : "0") . "</ISHTML>\r\n</OPTIONS> ', 0, 0, 8, 1, 0, 1, 100, 0)";
			
			if (! mysql_query($sql2)) {
				return 0;
			} else $listids[] = mysql_insert_id();
		}
		else if ($type == "date_box")
		{
			//INSERT INTO notices_custom (name, titre, type, datatype, options, multiple, obligatoire, ordre, search, export, exclusion_obligatoire, pond, opac_sort) VALUES
			//('TitreBulletin', 'Titre Bulletin', 'text', 'small_text', '<OPTIONS FOR="text">\r\n <SIZE>75</SIZE>\r\n <MAXSIZE>500</MAXSIZE>\r\n <REPEATABLE>0</REPEATABLE>\r\n</OPTIONS> ', 0, 0, 8, 1, 0, 1, 100, 0);
			
			$sql2 = "INSERT INTO " . $entity . "_custom (name, titre, type, datatype, options, multiple, obligatoire, ordre, search, export, exclusion_obligatoire, pond, opac_sort) VALUES";
			$sql2 .= " ('" . addslashes(str_replace(' ' , '', clean_string($value))) . "', '" . addslashes($titre) . "', '" . $type .  "', '" . $dataType . "', '<OPTIONS FOR=\"" . $type  . "\">\r\n <DEFAULT_TODAY>yes</DEFAULT_TODAY>\r\n <REPEATABLE>0</REPEATABLE>\r\n</OPTIONS> ', 0, 0, 8, 1, 0, 1, 100, 0)";
			
			if (! mysql_query($sql2)) {
				return 0;
			} else $listids[] = mysql_insert_id();
		}
		else
		{
			//INSERT INTO notices_custom (name, titre, type, datatype, options, multiple, obligatoire, ordre, search, export, exclusion_obligatoire, pond, opac_sort) VALUES
			//('TitreBulletin', 'Titre Bulletin', 'text', 'small_text', '<OPTIONS FOR="text">\r\n <SIZE>75</SIZE>\r\n <MAXSIZE>500</MAXSIZE>\r\n <REPEATABLE>0</REPEATABLE>\r\n</OPTIONS> ', 0, 0, 8, 1, 0, 1, 100, 0);
			
			$sql2 = "INSERT INTO " . $entity . "_custom (name, titre, type, datatype, options, multiple, obligatoire, ordre, search, export, exclusion_obligatoire, pond, opac_sort) VALUES";
			$sql2 .= " ('" . addslashes(str_replace(' ' , '', clean_string($value))) . "', '" . addslashes($titre) . "', '" . $type .  "', '" . $dataType . "', '<OPTIONS FOR=\"" . $type  . "\">\r\n <SIZE>75</SIZE>\r\n <MAXSIZE>500</MAXSIZE>\r\n <REPEATABLE>0</REPEATABLE>\r\n</OPTIONS> ', 0, 0, 8, 1, 0, 1, 100, 0)";
			
			if (! mysql_query($sql2)) {
				return 0;
			} else $listids[] = mysql_insert_id();
		}

	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idchamp'];
		}
	}

	if (count($listids) == 0) return 0;

	return $listids[0];

}


function setCPGeneric($entityId, $valeur, $cpId, $entity="notices", $type = "small_text") {
	// entity : could be : notice or expl	
	
	// type could be :
	// 	-	small_text
	// 	-	text
	//	-	integer
	//	-	date
	//	-	float
	
	if ($type != "small_text" && $type != "text" && $type != "integer" && $type != "date" && $type != "float")
		$type = "small_text";
	
		// echo "<br>import cp : valeur : " .$valeur . " <br>";
		if ($valeur && strlen(trim($valeur)) > 0 )  {
			$requete="insert into " . $entity .  "_custom_values (" . $entity .  "_custom_champ," . $entity .  "_custom_origine," . $entity .  "_custom_" . $type . ") values($cpId,$entityId,'".addslashes(clean_string($valeur))."')";
			// echo "<hr>debug<br>";
			// echo $requete;
			// echo "<br>end debug<hr>";
			mysql_query($requete);
		}
}

function setCPNotice($notice_id, $valeur, $cpId, $type = "small_text") {
	setCPGeneric($notice_id, $valeur, $cpId, "notices", $type);	
}

function setCPExemplaire($exemplaireId, $valeur, $cpId, $type = "small_text") {
	setCPGeneric($exemplaireId, $valeur, $cpId, "expl", $type);
}

function setCPEmprunteur($empr_id, $valeur, $cpId, $type = "small_text") {
	setCPGeneric($empr_id, $valeur, $cpId, "empr", $type);
}


function getBulletinIdFromExpl($numExemplaire) {
	// retourn id si peioriduqe existe
	// false sinon
	$requete="SELECT bulletin_id FROM bulletins,exemplaires WHERE bulletin_id = expl_bulletin and expl_cb = '" . addslashes($numExemplaire) . "'";
	
	$resultat=mysql_query($requete);
	if (@mysql_num_rows($resultat)) {
		//Si oui, récupération id
		return mysql_result($resultat,0,0);
	} else return FALSE;
}


function deleteThesaurus($thesaurusId) {
	
	$requete="delete from notices_categories inner join noeuds on num_noeud = id_noeud where num_thesaurus = $thesaurusId";
	mysql_query($requete);
	
	$requete="delete from notices_categories nc inner join categories c on nc.num_noeud = c.num_noeud where num_thesaurus = $thesaurusId";
	mysql_query($requete);
	
	$requete="delete from noeuds where num_thesaurus=$thesaurusId";
	mysql_query($requete);
	
	$requete="delete from categories where num_thesaurus=$thesaurusId";
	mysql_query($requete);
	
	$requete="delete from thesaurus where id_thesaurus=$thesaurusId";
	mysql_query($requete);
	
	
}

function deleteNotice($notice_id) {
	$requete="delete from notices where notice_id=$notice_id";
	mysql_query($requete);
	$requete="delete from responsability where responsability_notice=$notice_id";
	mysql_query($requete);
	$requete="delete from notices_fields_global_index where id_notice=$notice_id";
	mysql_query($requete);
	$requete="delete from notices_global_index where num_notice=$notice_id";
	mysql_query($requete);
	$requete="delete from notices_langues where num_notice=$notice_id";
	mysql_query($requete);
	$requete="delete from notices_mots_global_index where id_notice=$notice_id";
	mysql_query($requete);
	$requete="delete from notices_custom_values where notices_custom_origine=$notice_id";
	mysql_query($requete);
		
}

function modifyInPerio($titre, $noticeid) {
	
	$perio_id = periodiqueExiste($titre);

	if ($perio_id !== FALSE) {
		// le pério existe, on renvoie son id
		return $perio_id;
	} else {
		// le pério n'existe pas encore, on modiifie la noticeid en pério
		$update =" update notices set niveau_biblio = 's', niveau_hierar ='1' where notice_id = $noticeid";
		mysql_query($update);
		
		return $noticeid;
	}
}

function creerRelationMereFille($id_mere, $id_fille, $type_relation) {
	// type relation : g = titre suivant - précédent
	// i : traduction de : 

	$sql_ffi = 'insert into notices_relations (num_notice, linked_notice, relation_type, rank) VALUES ';
	$sql_ffi .= ' (' . $id_mere . ', ' . $id_fille . ', \'' . $type_relation . '\', 0)';

	if (! mysql_query($sql_ffi)) {
		echo "<br> erreur d'insertion de relations de dépouillement pour la requete : " . $sql_ffi . "<br>";
		return FALSE;
	} else return TRUE;
}

function import_editeur_sans_ville ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select ed_id from publishers where ed_name=\''.addslashes($valeur).'\'';
	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	
	// INSERT INTO publishers (ed_id, ed_name, ed_adr1, ed_adr2, ed_cp, ed_ville, ed_pays, ed_web, index_publisher, ed_comment) VALUES
	// (1, 'Éditions Racine', '', '', '', 'Bruxelles', '', '', ' editions racine ', '');
	
		$sql2 = "INSERT INTO publishers (ed_name, ed_adr1, ed_adr2, ed_cp, ed_ville, ed_pays, ed_web, index_publisher, ed_comment) VALUES ";
        $sql2 .= " ('" . addslashes($valeur) . "', '', '', '', '', '', '', ' " . strip_empty_words($valeur) . " ', '')";
				
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['ed_id'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}

function import_cp_in_list($notice_id, $valeur, $cp_id, $toSort = TRUE) {
	// echo "<br>import cp : valeur : " .$valeur . " <br>";
	
	if (strlen($valeur) == 0 ) return;
	
	$listids = array();
	
	/*
	INSERT INTO notices_custom_lists (notices_custom_champ, notices_custom_list_value, notices_custom_list_lib, ordre) VALUES
	(4, 'testvaleur2', 'Libellé test valeur 2', 0);
	*/
	
	$valeurTechnical = str_replace(array('(',')', ' '), '_', addslashes(strip_empty_words($valeur))); 
	
	$nbres = 0;
	
	// $sql = 'select notices_custom_list_value from notices_custom_lists where notices_custom_champ = ' . $cp_id . ' and notices_custom_list_lib = \'' . addslashes($valeur) . '\'';	
	
	$sql = 'select notices_custom_list_value from notices_custom_lists where notices_custom_champ = ' . $cp_id . ' and notices_custom_list_value = \'' . $valeurTechnical . '\'';
	
	$result = mysql_query($sql);
	if ($result) $nbres = mysql_num_rows($result);
	
	if (!$result) {
		echo "<br> error : " . $sql . "<br>";
		return 0;			
	} elseif ($nbres == 0 ) {
		// creer la liste
		
		$sql2 = "INSERT INTO notices_custom_lists (notices_custom_champ, notices_custom_list_value, notices_custom_list_lib, ordre) VALUES ";
		$sql2 .= " (" . $cp_id . ", '" . $valeurTechnical . "', '" . addslashes($valeur) . "', 0) ";
						
		if (! mysql_query($sql2)) {
				echo "<br> error : " . $sql2 . "<br>";
				return 0;
		} else $listids[] = $valeurTechnical;		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['notices_custom_list_value'];
		}
	}
	
	if ($notice_id != -1) import_cp($notice_id, $listids[0], $cp_id);
	
	
	if ($toSort) {
		// sort
		/*
		SET @rank := 0;   
		UPDATE `notices_custom_lists` SET ordre = (SELECT @rank := @rank + 1) ORDER BY notices_custom_list_value DESC; 
		*/
		
		$sql3 = "SET @rank := 0;";
		$sql4 = "UPDATE `notices_custom_lists` SET ordre = (SELECT @rank := @rank + 1) ORDER BY notices_custom_list_value; ";
		if (! mysql_query($sql3)) {
				echo "<br> error : " . $sql3 . "<br>";
				return 0;
		}
		if (! mysql_query($sql4)) {
				echo "<br> error : " . $sql4 . "<br>";
				return 0;
		}
	}
	
}


function import_statut_exemplaire ($valeur) {
	$valeur = trim($valeur);
	// $valeur = convertStatus($valeur);
	$listids = array();
	
	$sql = 'select idstatut from docs_statut where statut_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	
		$sql2 = "INSERT INTO docs_statut (statut_libelle, pret_flag, statusdoc_codage_import, statusdoc_owner, transfert_flag) VALUES ";
        $sql2 .= " ('" . addslashes($valeur) . "', 1, '', 0, 0)";
				
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idstatut'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}

function import_proprietaire_exemplaire ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select idlender from lenders where lender_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	// INSERT INTO lenders (idlender, lender_libelle) VALUES
	// (2, 'Fonds propre');
	
		$sql2 = "INSERT INTO lenders (lender_libelle) VALUES";
        $sql2 .= " ('" . addslashes($valeur) . "')";
		
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idlender'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}

function import_support_exemplaire ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select idtyp_doc from docs_type where tdoc_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	//INSERT INTO docs_type (idtyp_doc, tdoc_libelle, duree_pret, duree_resa, tdoc_owner, tdoc_codage_import, tarif_pret) VALUES
	//(1, 'testsupport', 31, 15, 0, '', '0.00');
	
		$sql2 = "INSERT INTO docs_type (tdoc_libelle, duree_pret, duree_resa, tdoc_owner, tdoc_codage_import, tarif_pret) VALUES";
        $sql2 .= " ('" . addslashes($valeur) . "', 31, 15, 0, '', '0.00')";
		
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idtyp_doc'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}


function import_codestatistiques_exemplaire ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select idcode from docs_codestat where codestat_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	
		// INSERT INTO docs_codestat (idcode, codestat_libelle, statisdoc_codage_import, statisdoc_owner) VALUES
		// (1, 'testcodestat', '', 0);
	
		$sql2 = "INSERT INTO docs_codestat (codestat_libelle, statisdoc_codage_import, statisdoc_owner) VALUES";
        $sql2 .= " ('" . addslashes($valeur) . "', '', '0')";
		
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idcode'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}

function getAllSectionsIds() {
	$listids = array();
	
	$sql = 'select idsection from docs_section';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {		
		return $listids;	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idsection'];
		}
	}
	
	return $listids;
	
}


function getAllLocalisationsIds() {
	$listids = array();
	
	$sql = 'select idlocation from docs_location';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {		
		return $listids;	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idlocation'];
		}
	}
	
	return $listids;
	
}



function getSupportsIds($libelles = array(), $toInclude = 1) {
	$listids = array();

	$where = "";

	if ($toInclude == 0) // exclusion
	{
		foreach ($libelles as $libelle) {
			$where .= (strlen($where) == 0 ? "" : " and") . " tdoc_libelle != \"". mysql_escape_string($libelle)  . "\"";
		}
	} else // inclusion
	{
		foreach ($libelles as $libelle) {
			$where .= (strlen($where) == 0 ? "" : " or") . " tdoc_libelle = \"". mysql_escape_string($libelle)  . "\"";
		}
	}

	$sql = 'select idtyp_doc from docs_type ' . (strlen($where) > 0 ? ' where ' . $where : '') ;
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listids;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idtyp_doc'];
		}
	}

	return $listids;

}



function getAllSupportsIds() {
	$listids = array();

	$sql = 'select idtyp_doc from docs_type';
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listids;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idtyp_doc'];
		}
	}

	return $listids;

}



function getCategoriesIds($libelles = array(), $toInclude = 1) {
	$listids = array();
	
	$where = "";
	
	if ($toInclude == 0) // exclusion 
	{
		foreach ($libelles as $libelle) {
			$where .= (strlen($where) == 0 ? "" : " and") . " libelle != \"". mysql_escape_string($libelle)  . "\"";
		}
	} else // inclusion 
	{
		foreach ($libelles as $libelle) {
			$where .= (strlen($where) == 0 ? "" : " or") . " libelle = \"". mysql_escape_string($libelle)  . "\"";
		}
	}	
	
	$sql = 'select id_categ_empr from empr_categ ' . (strlen($where) > 0 ? ' where ' . $where : '') ;
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listids;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['id_categ_empr'];
		}
	}
	
	return $listids;
	
}

function import_localisation_exemplaire ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select idlocation from docs_location where location_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	
		// INSERT INTO docs_location (idlocation, location_libelle, locdoc_codage_import, locdoc_owner, location_pic, location_visible_opac, name, adr1, adr2, cp, town, state, country, phone, email, website, logo, commentaire, transfert_ordre, transfert_statut_defaut, num_infopage, css_style, surloc_num, surloc_used, show_a2z) VALUES
		// (1, 'testlocalisation', '', 0, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', 9999, 0, 0, '', 0, 0, 0);
	
		$sql2 = "INSERT INTO docs_location (location_libelle, locdoc_codage_import, locdoc_owner, location_pic, location_visible_opac, name, adr1, adr2, cp, town, state, country, phone, email, website, logo, commentaire, transfert_ordre, transfert_statut_defaut, num_infopage, css_style, surloc_num, surloc_used, show_a2z) VALUES";
        $sql2 .= " ('" . addslashes($valeur) . "', '', 0, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', 9999, 0, 0, '', 0, 0, 0)";
		
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
		
		// insert docsloc_section
		
		$sectionsIds = getAllSectionsIds();
		
		foreach ($sectionsIds as $sectionId) {
		// INSERT INTO docsloc_section (num_section, num_location, num_pclass) VALUES
		// (1, 1, 0);
			$sql3 = "INSERT INTO docsloc_section (num_section, num_location, num_pclass) VALUES" ;
			$sql3 .= " (" . $sectionId . ", " . $listids[0] . ", 0)";
			mysql_query($sql3);
		}
		
		
	
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idlocation'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}




function import_section_exemplaire ($valeur) {
	$valeur = trim($valeur);	
	$listids = array();
	
	$sql = 'select idsection from docs_section where section_libelle =  \'' . addslashes($valeur) . '\'';	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result) {		
		return 0;	
	} elseif ($nbres == 0 ) {
	
		// INSERT INTO docs_section (idsection, section_libelle, sdoc_codage_import, sdoc_owner, section_pic, section_visible_opac) VALUES
		// (1, 'testsection', '', 0, '', 1);
	
		$sql2 = "INSERT INTO docs_section (section_libelle, sdoc_codage_import, sdoc_owner, section_pic, section_visible_opac) VALUES";
        $sql2 .= " ('" . addslashes($valeur) . "', '', 0, '', 1)";
		
		if (! mysql_query($sql2)) {
				return 0;
		} else $listids[] = mysql_insert_id();		
		
		// insert docsloc_section
		$localisationsIds = getAllLocalisationsIds();
		
		foreach ($localisationsIds as $localisationId) {
		// INSERT INTO docsloc_section (num_section, num_location, num_pclass) VALUES
		// (1, 1, 0);
			$sql3 = "INSERT INTO docsloc_section (num_section, num_location, num_pclass) VALUES" ;
			$sql3 .= " (" . $listids[0]  . ", " . $localisationId . ", 0)";
			mysql_query($sql3);
		}
		
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['idsection'];
		}
	}
	
	if (count($listids) == 0) return 0;
	
	return $listids[0];
	
}

// fonction de gestions du th�saurus et des cat�gories

function importCategoryNotice($noticeId, $categoryId, $order = 0) {
	save_categ_for_notice($categoryId,$order,$noticeId);
}


function importCategoryThesaurus($categName, $thesaurusName, $lang) {

	$thesaurusId = importThesaurus($thesaurusName);

	if ($thesaurusId === false) return false;

	$categoryId = find_categ($categName,$thesaurusId,$lang);

	if ($categoryId !== 0) {
		return $categoryId;
	} else {
		// on ajoute les categories avec TOP comme "p�re"
		// TODO : g�rer l'arborescence
		// $nodeRacineId = getRacineForThesaurus($thesaurusId);
		
		// specific for Ludos 
		echo "Cat�gorie non trouv�e : " . $categName . "<br>";
		$nodeRacineId = getNonClasseForThesaurus($thesaurusId);

		return add_categ($categName,$thesaurusId,$nodeRacineId,$lang);
	}
}

function isThesaurusExisting ($thesaurusName) {
	// return false if no thesaurus
	// return ID if thesaurus existing
	
	$thesaurusName = trim($thesaurusName);
	$listids = array();
	
	$sql = 'select id_thesaurus from thesaurus where libelle_thesaurus=\''.addslashes($thesaurusName).'\'';
	
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	
	if (!$result || $nbres ==0)  {
		return false;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['id_thesaurus'];
		}		
	}
	
	if (count($listids) == 0) return FALSE;
	
	$idThesaurus = $listids[0];
	
	return $idThesaurus;
	
}



function importThesaurus($thesaurusName, $lang="fr_FR") {
	// retourne l'id du thesarusu s'il existe
	// sinon cr�er le th�saurus et les cat�gories de base

	$thesaurusName = trim($thesaurusName);
	$listids = array();

	$sql = 'select id_thesaurus from thesaurus where libelle_thesaurus=\''.addslashes($thesaurusName).'\'';

	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);

	$newThesaurus = FALSE;

	if (!$result) {
		return 0;
	} elseif ($nbres == 0 ) {

		// creation du thesaurus ...
		$sql2 = "INSERT INTO thesaurus (libelle_thesaurus, langue_defaut, active, opac_active, num_noeud_racine) VALUES ('" . addslashes($thesaurusName) . "', '" . $lang . "', '1', '1', 1)";
		// attention il faudra changer le nom racine ...

		if (! mysql_query($sql2)) {
			return FALSE;
		} else $listids[] = mysql_insert_id();

		$newThesaurus = TRUE;

	} else {
		while ($row = mysql_fetch_array($result)) {
			$listids[]	= $row['id_thesaurus'];
		}
	}

	if (count($listids) == 0) return FALSE;

	$idThesaurus = $listids[0];

	if ($newThesaurus) {
		// nouveau th�saurus: on doit cr�er les cat�gories de base

		// TOP
		$sql = "INSERT INTO `noeuds` (`autorite`, `num_parent`, `num_renvoi_voir`, `visible`, `num_thesaurus`) VALUES ('TOP', 0, 0, '0', " . $idThesaurus . ")";
		mysql_query($sql);

		$topNodeId = mysql_insert_id();

		// update du thesaurus
		$sql = "update thesaurus set num_noeud_racine = " . $topNodeId . " where id_thesaurus = " . $idThesaurus;
		mysql_query($sql);


		// non classes
		$sql = "INSERT INTO `noeuds` (`autorite`, `num_parent`, `num_renvoi_voir`, `visible`, `num_thesaurus`) VALUES ('NONCLASSES', ". $topNodeId . ", 0, '0', " . $idThesaurus . ")";
		mysql_query($sql);
		$currentNodeId =  mysql_insert_id();
		$sql = "INSERT INTO categories (num_noeud, langue, libelle_categorie, note_application, comment_public, comment_voir, index_categorie) VALUES (" . $currentNodeId .  ", '" . $lang . "', '~termes non class�s', '', '', '', ' termes non classes ')";
		mysql_query($sql);

		// orphelins
		$sql = "INSERT INTO `noeuds` (`autorite`, `num_parent`, `num_renvoi_voir`, `visible`, `num_thesaurus`) VALUES ('ORPHELINS', " . $topNodeId . ", 0, '0', " . $idThesaurus. ")";
		mysql_query($sql);
		$currentNodeId =  mysql_insert_id();
		$sql = "INSERT INTO categories (num_noeud, langue, libelle_categorie, note_application, comment_public, comment_voir, index_categorie) VALUES ("  . $currentNodeId . ", '" . $lang . "', '~termes orphelins', '', '', '', ' termes orphelins ')";
		mysql_query($sql);

	}

	return $idThesaurus;

}

function getRacineForThesaurus($idThesaurus) {

	$sql = "select num_noeud_racine from thesaurus where id_thesaurus = " . $idThesaurus;

	$result = mysql_query($sql);

	if ($result === FALSE) return false;

	$nbres = mysql_num_rows($result);

	if ($nbres == 0) return 0;

	return mysql_result($result,0,0);

}

function getNonClasseForThesaurus($idThesaurus) {
	$sql = "select id_noeud from noeuds where autorite = '" . "NONCLASSES" . "' and num_thesarus = " . $idThesaurus;
	$result = mysql_query($sql);

	if ($result === FALSE) return false;

	$nbres = mysql_num_rows($result);

	if ($nbres == 0) return 0;

	return mysql_result($result,0,0);
}

function getCurrentNoeudIdForThesaurus($idThesaurus) {

	// NE SERT A RIEN DU TOUT !!!!!!!!!

	// retourne l'ID du noeud pour le th�saurus
	// si pas de noeud => 0
	// si un noeud => last Id + 1
	// => le r�sultat est directement exploitable pour ins�rer un noeud

	$sql = "select max(id_noeud) as max_noeud from noeuds where num_thesaurus = " . $idThesaurus;

	$result = mysql_query($sql);

	if ($result === FALSE) return false;

	$nbres = mysql_num_rows($result);

	if ($nbres == 0) return 0;

	$row = mysql_fetch_array($result);

	$id = $row['max_noeud'];

	if (is_null($id)) return 0;

	return $id++;

}


//cat�gories
function find_categ($term,$id_thesaurus,$lang){
	$categ_id = categories::searchLibelle(addslashes($term),$id_thesaurus,$lang);
	if($categ_id){
		//le terme existe
		$noeud = new noeuds($categ_id);
		if($noeud->num_renvoi_voir){
			$categ_to_index = $noeud->num_renvoi_voir;
		}else{
			$categ_to_index = $categ_id;
		}
	}else{
		$categ_to_index=0;
	}
	return $categ_to_index;
}

function add_categ($term,$id_thesaurus,$non_classes,$lang){
	$n = new noeuds();
	$n->num_thesaurus = $id_thesaurus;
	$n->num_parent = $non_classes;
	$n->save();
	$c = new categories($n->id_noeud, $lang);
	$c->libelle_categorie = $term;
	$c->index_categorie = ' '.strip_empty_words($term).' ';
	$c->save();
	return $n->id_noeud;
}

function add_categ_alphabetical($term,$id_thesaurus,$lang){
	$firstChar = $term;
	//$firstChar = substr($firstChar, 0, 1);
	$firstChar = $firstChar{0};
	if (ctype_alpha($firstChar)){
		$idCat = find_categ(strtoupper($firstChar), -1, $lang);
	}
	else {
		$idCat = find_categ("autre", -1, $lang);
	}
	$n = new noeuds();
	$n->num_thesaurus = $id_thesaurus;
	$n->num_parent = $idCat;
	$n->save();
	$c = new categories($n->id_noeud, $lang);
	$c->libelle_categorie = $term;
	$c->index_categorie = ' '.strip_empty_words($term).' ';
	$c->save();
	return $n->id_noeud;
}

function save_categ_for_notice($categ_to_index,$ordre_categ,$notice_id){
	$requete = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) VALUES(".$notice_id.",".$categ_to_index.",".$ordre_categ.")";
	mysql_query($requete);
}

function save_categ($notice_id, $categ_to_index,$ordre_categ){
	// LEGACY : � ne pas utiliser => pr�f�rer la fonction save_categ_for_notice
	global $notice_id;
	$requete = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) VALUES(".$notice_id.",".$categ_to_index.",".$ordre_categ.")";
	mysql_query($requete);
}

// END Fonction th�saurus

function periodiqueExiste($nomPeriodique) {
	// retourn id si peioriduqe existe
	// false sinon
	$requete="select notice_id from notices where tit1='".addslashes($nomPeriodique)."' and niveau_hierar='1' and niveau_biblio='s'";
	$resultat=mysql_query($requete);
	if (@mysql_num_rows($resultat)) {
		//Si oui, récupération id
		return mysql_result($resultat,0,0);
	} else return FALSE;
}

function periodiqueExisteLIKE($nomPeriodique) {
	// retourn id si peioriduqe existe
	// false sinon
	$requete="select notice_id from notices where tit1 like'%".addslashes($nomPeriodique)."%' and niveau_hierar='1' and niveau_biblio='s'";
	$resultat=mysql_query($requete);
	if (@mysql_num_rows($resultat)) {
		//Si oui, récupération id
		return mysql_result($resultat,0,0);
	} else return FALSE;
}

function createSerial($perio_info) {
	// $period_info :
	// 		titre
	
	
	$chapeau=new serial();
	$info=array();
	$info['tit1']=addslashes($perio_info['titre']);
	/*$info['tit3']=addslashes($titre_revue_parallele);
	$info['tit4']=addslashes($titre_revue_complements);
	// echo "debug note revue : " . $note_revue ."<br>";
	$info['n_gen'] = addslashes($note_revue);*/
	
	$info['niveau_biblio']='s';
	$info['niveau_hierar']='1';
	$info['typdoc']='b'; // enviro
			
	$chapeau->update($info);
	$chapeau_id=$chapeau->serial_id;

	return $chapeau_id;
}

function genere_perio_bulletin($perio_info,$bull_info,$statutnot, $notice_id, $createSerial = true){
	
	// IN : 
	// $period_info : 
	// 		titre
	// $bull_info :
	// 		date
	//		num
	//		mention
	//		titre
	
	// OUT : 
	// un array : 
	//	bulletinnoticeid : id de la notice du bulletin créée
	//  bulletinid : id du bulletin créé
	// OU FALSE si createSerial est à false et que le périodique n'existe pas
	
	
	
	//on récup et/ou génère le pério
	// $perio_id = genere_perio($perio_info);

	if($bull_info['mention'] ==""){
		// $bull_info['mention'] = substr($bull_info['date'],8,2)."/".substr($bull_info['date'],5,2)."/".substr($bull_info['date'],0,4);
		// jouer avec les dates ???
		$format = 'Y-m-d';
		$date = DateTime::createFromFormat($format, $bull_info['date']);
		$bull_info['mention'] = $date->format('F Y');
		$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		$bull_info['mention'] = str_replace($english_months, $french_months, $bull_info['mention']);
	}

	if ($bull_info['titre'] == "") {
		$bull_info['titre'] = $bull_info['num'] . " - " . $bull_info['mention'] ;
	}

	// on regarde si le périodique existe déjà => si oui, deux possibilités :
	//			- soit le bulletin n'existe pas encore => on transforme la notice de monographie créée en bulletin du périodique
	//			- soit le bulletin existe déjà => il faut supprimer la notice de monographie créée
	// si le périodique n'existe pas encore => il faut
	// 			1. transformer la notice de monographie créée en périodique (si createSerial est à true)
	//			2. ajouter le bulletin

	$perio_id = -1;
	$bulletin_notice_id = -1;
	$bulletin_id = -1;
	
	$perio_id = periodiqueExisteLIKE($perio_info['titre']);

	if ($perio_id == FALSE) {
		// le pério n'existe pas ....
		// on le crée ?
		if ($createSerial) {
			// on crée le serial
			$perio_id = createSerial($perio_info);
		} else {
			// on ne crée pas le serial => on retourne false ????
			// faut-il effacer la notice ???
			return false;
		}
		
	}
	
	/*$search2 = "select notice_id from notices where tit1 like '".addslashes($perio_info['titre']) . "' and niveau_biblio = 'm'";
	
	$res2 = mysql_query($search2);
	// echo $search2 . ' = ' . mysql_num_rows($res2). '<hr>';
	
	if(mysql_num_rows($res2) == 1) {
		// le périodique existe deja, et on a une notice de type "m" => on va la transformer en bulletin
		$bulletin_notice_id = mysql_result($res2,0,0);
		// correction Juillet 2012 : sauf s'il existe déjà => sinon on crée 2 fois le bulletin...		
	}
	*/
		
	//Bulletin existe-t-il ?
	$search3="select bulletin_id,num_notice from bulletins where date_date  = '". addslashes($bull_info['date']) ."' and mention_date = '". addslashes($bull_info['mention']) . "' and bulletin_titre = '" . addslashes($bull_info['titre']) . "' and bulletin_numero = '". addslashes($bull_info['num']) ."' and  bulletin_notice = $perio_id";
		
	$res3 = mysql_query($search3);
	
	if (mysql_num_rows($res3)) {
		//Si oui, récupération id bulletin
		$bulletin_id=mysql_result($res3,0,0);
		
		// Et récupération de l'id éventuel de la notice de bulletin !!!
		$bulletin_notice_id = mysql_result($res3,0,1);

		if ($bulletin_notice_id === FALSE || $bulletin_notice_id == -1) {
			//echo '<br>DEBUG FFI : ' . $search3 . ' = ' . mysql_num_rows($res3). '<hr>';
			// echo "<br>DEBUG FFI : " . $search3;
			//echo "<br>DEBUG FFI (suite) : " . $bull_info['titre'] . "/" . $perio_info['titre'] ;
		}
	}

	if ($bulletin_id  == -1) {
		// pas de bulletin pré-existant => on transforme la notice monographique en bulletin
		// typdoc = "b" pour BXL Environnement
		$update = " update notices set " . "typdoc = 'b', "  . "niveau_biblio = 'b', niveau_hierar ='2', year='" . addslashes($bull_info['date']) . "', tit1 = '". addslashes($bull_info['titre']) . "' where notice_id = $notice_id";
		
		mysql_query($update);
		
		// pas de bulletin pré-existant => on crée un bulletin
		$insert = "insert into bulletins set date_date  = '". addslashes($bull_info['date']) ."', mention_date = '".addslashes($bull_info['mention'])."', bulletin_titre = '" . addslashes($bull_info['titre']) . "', " . "bulletin_numero = '".addslashes($bull_info['num'])."', bulletin_notice = $perio_id";
		$insert .=", num_notice = $notice_id";
		$result = mysql_query($insert);
		$bulletin_id = mysql_insert_id();
		
		$bulletin_notice_id = $notice_id;
		
				
	} elseif ($bulletin_id && ! $bulletin_notice_id) {
		// on a un bulletin, mais pas de notice de bulletin correspondante !!!!
		// pas de bulletin pré-existant => on transforme la notice monographique en bulletin
		// typdoc = "b" pour BXL Environnement
		$update = " update notices set " . "typdoc = 'b', "  . "niveau_biblio = 'b', niveau_hierar ='2', year='" . addslashes($bull_info['date']) . "', tit1 = '". addslashes($bull_info['titre']) . "' where notice_id = $notice_id";
		mysql_query($update);
		$bulletin_notice_id = $notice_id;
		
		// on met à jour la table bulletin pour que le bulletin pointe vers cette notice
		$updateBulletin = " update bulletins set num_notice = $bulletin_notice_id where bulletin_id = $bulletin_id";
		mysql_query($updateBulletin);
		
	} else {
		// on avait déjà un bulletin, et une notice associée !!!
		// => on supprime la monographie créée
		deleteNotice($notice_id);
		// echo $delete . ' = ' . $t . '<hr>';		
	}
	
	$res = array();
	$res['bulletinnoticeid'] = $bulletin_notice_id;
	$res['bulletinid'] = $bulletin_id;  

	return $res;
}

function create_docnumExisting($noticeId, $bulletinId, $titre, $filename, $extension = "pdf", $mime = 'application/pdf', $idRepertoire = 1, $isLink = FALSE){
	
	/* for future generalization of this function ;)
	$rep = new upload_folder($idRepertoire);
	$name = ($doc['nom_fic'] ? $doc['nom_fic'] : $doc['titre']);
	$filename = strtolower(implode("_",explode(" ",$name)));

	$filename = checkIfExist($rep->repertoire_path,$filename,$filename);
	file_put_contents($rep->repertoire_path.$filename,file_get_contents($doc['url']));
	$ext_fichier = extension_fichier($filename);
	if($doc['mimetype'] == ""){
		create_tableau_mimetype();
		$mimetype = trouve_mimetype($filename,$ext_fichier);
	}else $mimetype = $doc['mimetype'];
	*/

	$insert = "insert into explnum set ";
	if($bulletinId != 0) $insert.= "explnum_bulletin = $bulletinId, ";
	else $insert.= "explnum_notice = $noticeId, ";
	
	if ($isLink === FALSE) {
		$insert.= "explnum_nom = '".addslashes($titre)."', ";
		$insert.= "explnum_mimetype = '$mime', ";
		$insert.= "explnum_nomfichier = '".addslashes($filename)."', "; // attention avec extension !!!
		$insert.= "explnum_extfichier = '" . $extension . "', ";  // sans le point !
		$insert.= "explnum_repertoire = " . $idRepertoire. ", ";
		$insert.= "explnum_path = '/'";
	} else {
		$insert.= "explnum_nom = '".addslashes($filename)."', ";		
		$insert.= "explnum_mimetype = 'URL', ";
		$insert.= "explnum_repertoire = 0, ";
		$insert.= "explnum_url = '". addslashes($titre) .  "'";
	}
	$result = mysql_query($insert);
	$explnum_id = mysql_insert_id();
	return $explnum_id;
}

function getAllNotices() {
	$listNotices = array();

	$sql = 'select * from notices';
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listNotices;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listNotices[]	= $row;
		}
	}

	return $listNotices;
}

function getAllExemplaires() {
	$listExpl = array();

	$sql = 'select * from exemplaires';
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listExpl;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listExpl[]	= $row;
		}
	}

	return $listExpl;
}

function getAllEmprunteurs() {
	$listEmpr = array();

	$sql = 'select * from empr';
	$result = mysql_query($sql);
	$nbres = mysql_num_rows($result);
	if (!$result || $nbres == 0) {
		return $listEmpr;
	} else {
		while ($row = mysql_fetch_array($result)) {
			$listEmpr[]	= $row;
		}
	}

	return $listEmpr;
}

function mb_pathinfo($filepath) {
	preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);
	if($m[1]) $ret['dirname']=$m[1];
	if($m[2]) $ret['basename']=$m[2];
	if($m[5]) $ret['extension']=$m[5];
	if($m[3]) $ret['filename']=$m[3];
	return $ret;
}


?>