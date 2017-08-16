<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_other.inc.php,v 1.16 2015-04-03 11:16:22 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// enregistrement de la notices dans les catégories
function traite_categories_enreg($notice_retour,$categories,$thesaurus_traite=0) {

	global $dbh;
	/*
	// si $thesaurus_traite fourni, on ne delete que les catégories de ce thesaurus, sinon on efface toutes
	//  les indexations de la notice sans distinction de thesaurus
	if (!$thesaurus_traite) $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' ";
	else $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' and num_noeud in (select id_noeud from noeuds where num_thesaurus='$thesaurus_traite' and id_noeud=notices_categories.num_noeud) ";
	$res_del = @pmb_mysql_query($rqt_del, $dbh);
	
	$rqt_ins = "insert into notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
	
	for($i=0 ; $i< sizeof($categories) ; $i++) {
		$id_categ=$categories[$i]['categ_id'];
		if ($id_categ) {
			$rqt = $rqt_ins . " ('$notice_retour','$id_categ', $i) " ; 
			$res_ins = @pmb_mysql_query($rqt, $dbh);
		}
	}*/
	
	/* @var $category categories */
	
	$rqt_ins = "insert into notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
	
	$i=0;
	foreach ($categories as $categoryAll) {
		$categoriId = $categoryAll['f_categ_id_ludo'];		
		if ($categoriId) {
			$rqt = $rqt_ins . " ('$notice_retour','$categoriId', $i) " ;
			$res_ins = @pmb_mysql_query($rqt, $dbh);
			$i++;
		}
	}
	
}

// function traite_categories_for_form($tableau_600="",$tableau_601="",$tableau_602="",$tableau_605="",$tableau_606="",$tableau_607="",$tableau_608="") {
function traite_categories_for_form($categories) {
	global $charset, $msg, $pmb_keyword_sep, $rameau;
	/* @var $category categories */
	$champ_rameau = ""; // categories
	
	$formFieldsCategoriesInput = "";
	$formFieldsCategoriesLabels = "";
	$formFieldsNotFoundCategories = "";
	$categoriesNotFound = array();
	$counter = 0;
	
	foreach ($categories as $categoryAll) {
		$category = $categoryAll['category'];				
		$categoryLocal =  new categories($category->num_noeud, "fr_FR");
		if ($categoryLocal->libelle_categorie == $category->libelle_categorie) {
			$no = new noeuds($category->num_noeud);
			$th = new thesaurus($no->num_thesaurus);
			// on a trouvé la catégorie identique dans la db locale
			$formFieldsCategoriesLabels .=  '[' . $th->libelle_thesaurus . ']' . ' : ' . $category->libelle_categorie  . "<br>" ; 
			$formFieldsCategoriesInput .=   "<input type='hidden' name='f_categ_id_ludo" . $counter . "' value='" . $category->num_noeud .   "' >";
			$counter++;
			
		} else {
			// on n'a pas trouvé de catégories équivalentes en locales
			$categoriesNotFound[] = $categoryAll;
			$formFieldsNotFoundCategories .= $category->libelle_categorie . $pmb_keyword_sep; 
		}
		 
	}
	
	return array(
			"form" => $formFieldsCategoriesInput, 
			// htmlentities($formFieldsCategories,ENT_QUOTES,$charset),
			"message" => "<div style='margin-left:30px;'><br>" . $formFieldsCategoriesLabels . "</div><br>" . "Les catégories ci-dessus ont trouvé une correspondance et seront intégrées automatiquement." . 
						 ( strlen($formFieldsNotFoundCategories) > 0 ?  "<br>Les mots-clés suivants seront intégrés en zone de mots clés libres : <b>".htmlentities($formFieldsNotFoundCategories,ENT_QUOTES,$charset)."</b>" : "" ) .
						"<input type='hidden' name='rameau' value='".htmlentities($formFieldsNotFoundCategories,ENT_QUOTES,$charset)."' />",
	
	);
	
	
}


function traite_categories_from_form() {
	global $rameau, $categ_pas_trouvee ;
	global $dbh;
	global $thes;
	
	global $max_categ ;
	global $pmb_keyword_sep;
	
	// traitement des catégories correspondantes
	$categories = array () ;
	// for ($i=0; $i< $max_categ ; $i++) {
	for ($i=0; $i< 10 ; $i++) {
		$var_categ = "f_categ_id_ludo$i" ;
		global $$var_categ ;
		if ($$var_categ) 
			$categories[] = array('f_categ_id_ludo' => $$var_categ );
		}
		
	// traitement des catégories non trouvées
	if (strlen($rameau) > 0) {
		$categ_pas_trouvee = explode($pmb_keyword_sep, $rameau);		
	}
	
	return $categories ;
}
