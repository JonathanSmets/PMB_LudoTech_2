<?php

	include('util.php');

	$patch_id = "migration Ludotheques";
	$simulation = TRUE;
	$debug = FALSE;
	
	define('THESAURUS_MOT_CLEF', "Mots clés ");
		
	global $dbh;
	
	echo "<h1>Lancement patch " . $patch_id . " </h1>";
	
	/*
	 * TODO : 
	 * 	- OK : pour les lecteurs qui n'ont pas de mot de passe =>    listing des emails
	 *  - OK : afficher la somme à réclamer par lecteur dans la fiche lecteur globale (même ce qui n'a pas été validé)
	 *  - cacher "non encaissé" dans la caisse (en attente de validation par le client)
	 *  OK - tester numéro gsm sur le serveur 
	 *   - prolongation  allow_extended_fee : coût de la prolongation doit être égal au coût du prêt (2 semaines de prolongation)
	 *  OK  - vérifier catégories dans Z3950
	 *   - offre formation : relances multiples, réservations, gestion des caisses
	 *   - adapter les fiches emprunteur et notice selon copie d'écran 
	 * */
	
	
	// ETAPE 0 : thésaurus mots clés

	$thesaurusID = isThesaurusExisting(THESAURUS_MOT_CLEF);
	
	if ($thesaurusID === FALSE) {
		// on va créer le thésaurus et créer les entrées
		$thesaurusID = importThesaurus(THESAURUS_MOT_CLEF, "fr_FR");
			
		$DictionnaryTerms = array(
				"Compétences" => array("Action Tri","Argumentation","Classement","Discrimination auditive","Discrimination olfactive","Discrimination tactile ","Discrimination visuelle","Ecriture/gestion graphique","Enfilage ","Expression corporelle","Expression orale ","Fractions","Géométrie","Gestion de données","Grandeurs","Heure","Laçage","Langue ","Latéralité","Lecture","Logique","Mathématiques","Mémoire","Mesures","Nombre","Opérations mathématiques","Organisation spatiale ","Orthographe","Psychomotricité","Quantités","Réflexes","Réflexion","Repérage spatio-temporel","Vocabulaire"),
				"Contexte d'utilisation" => array("Ambiance","Anniversaire","Jeux à deux ","Jeux d'eau","Jeux d'équipes","Jeux d'extérieur","Jeux dans le noir ","Jeux de cartes","Jeux familiaux","Jeux magnétiques","Jeux pour 1 joueur","Jeux pour 6 joueurs ou +","Jeux pour joueurs avertis ","Jeux surdimensionnés"),
				"Mécanismes" => array("Adresse","Affrontement","Blocage","Bluff","Capture","Casse-tête","Choix simultanés ","Collections/familles","Commerce","Connaissance","Connexion de tuiles ","Conquête","Construction","Coopération","Course","Créativité","Deck building","Déduction","Défausse","Défi","Dessin","Draft","Echange/troc","Empilement","Enchères","Enigme","Equilibre","Gestion","Imagination","Improvisation","Jeux de lancer","Jeux de majorité","Jeux de paris","Jeux de plis ","Négociation","Objectif secret ","Observation","Observation","Paires","Parcours","Placement","Prise de risques ","Programmation","Questions réponses","Rapidité","Rôles","Stop ou encore ","Stratégie"),
				"Thèmes" => array("Abstrait ","Afrique","Agriculture","Alimentation","Alphabet","Animaux aquatiques ","Animaux de la ferme ","Animaux domestiques","Animaux sauvages","Antiquité","arbre","Argent","Armée","Art","Asie ","Aventure","Aviation","Bande desssinée ","Bateau","Belgique","Bijou","Billes","Campagne ","Carnaval","Casino","Chanson","Château","Chevalier ","Chiffre","Cinéma","Cirque","Code ","Code de la route","Contes","Continent","Corps humain","Couleurs","Cuisine","Culture générale","Déguisement","Désert","Détective","Détective","Dînette","Dinosaures","Disney","Docteur","Dragon","Eau","Ecole","Ecologie","Economie","Egypte ","Elevage ","Emotions","Engrenages","enquête","enquête","Espace","Europe","Exploration","Famille","Fantastique","Fantôme","Far west","Fée","Ferme","flore","Forêt","Formes","fruits","Garage","Géographie","Guerre","Halloween","Histoire","Ile","Indiens","Insectes","Jardin","Jungle","Label Ludo","Labyrinthe","Légendes","Légendes","Littérature","Loto","Loup","Magasin","Magicien","Maison","Marionnettes","Médical","Mer","Météo","Métiers","Mime","Mine","Miroir","Monde ","Monstre","Montagne","Musique","Mythologie","Nature","Oeuf","Oiseaux","Orient","Outils","Pâques","Perles","Peur","Pirates","Poissons","Police","Pompier","Préhistoire","Prince(sse)","Reptiles","Robots","Rythmes","Saisons","Sciences","Secret","Sécurité","Sens ","Sorcier","Sport","Symbole","Télévision","Temps (durée)","Terre","Théätre","Train","Transport","Trésor","Vampire","Vêtements","Ville","Voitures","Voyage","Zombie")
		);
	
			
		$racineID = getRacineForThesaurus($thesaurusID);
			
		foreach ($DictionnaryTerms as $key => $values) {
	
			$noeudFirstLevelID = add_categ($key,$thesaurusID,$racineID,"fr_FR");
	
			foreach ($values as $value) {
				add_categ($value,$thesaurusID,$noeudFirstLevelID,"fr_FR");
			}
	
		}
	}
	
	
	// ETAPE 1 : conversion des champs de notices
	
	// 1.1. pour toutes les notices : on prend la liste des colonnes ajoutées par la COCOF
	// 1.2. pour chaque champ ajouté on crée le champ personnalisé et on importe la valeur
	
	// par exemple : 
	//import_cp($notice_id, $valeur, importCPNotice("NombreJoueurs", "Nombre de joueurs"));
	
	$listNotices = getAllNotices();
	
	
	foreach ($listNotices as $notice)
	{
		setCPNotice($notice['notice_id'], $notice['nbmin'], importCPNotice("nbr_min_joueurs", "Nombre minimum de joueurs", "text", "integer"), "integer");
		setCPNotice($notice['notice_id'], $notice['nbmax'], importCPNotice("nbr_max_joueurs", "Nombre maximum de joueurs", "text", "integer"), "integer");
		setCPNotice($notice['notice_id'], $notice['agemin'], importCPNotice("age_min", "Age minimum", "text", "float"), "float");
		setCPNotice($notice['notice_id'], $notice['agemax'], importCPNotice("age_max", "Age maximum", "text", "float"), "float");
		setCPNotice($notice['notice_id'], $notice['duree'], importCPNotice("duree_moy", "Durée moyenne (minutes)", "text", "integer"), "integer");
		
		// principe du jeu => n_principe
		// analyse pédagogique => n_analyse
		// règles du jeu si pas un PDF => n_regle
		// contenu => n_contenu
		setCPNotice($notice['notice_id'], $notice['n_principe'], importCPNotice("principe_jeu", "Principe du jeu", "comment", "text"), "text");
		setCPNotice($notice['notice_id'], $notice['n_analyse'], importCPNotice("analyse_pedagogique", "Analyse pédagogique", "comment", "text"), "text");
		$lastChars = substr($notice['n_regle'], -3);
		if ($lastChars != "pdf" && $lastChars != "PDF")
		{
			setCPNotice($notice['notice_id'], $notice['n_regle'], importCPNotice("regles_jeu", "Règles du jeu", "comment", "text"), "text");
		}
		else 
		{
			// ATTENTION pour que ça fonctionne il faut que le script DBMIGRATION LOG #0002 soit lancé (création du répertoire d'upload)
			// @TODO : définir le répertoire d'upload avec le bon chemin serveur de production
			//LC 08/04/2016 : Needed to use a tmp var and my own method defined in utils.php or I was getting a fatal error : "Call to undefined function path_info()"
			//$nomFichierInfos = path_info($notice['n_regle']); // function path_info (définie par PHP, voir : http://php.net/manual/fr/function.pathinfo.php)
			$tmp_v = $notice['n_regle'];
			$nomFichierInfos = mb_pathinfo($tmp_v);
			$nomFichier=$nomFichierInfos['basename'];
			
			if (stripos($notice['n_regle'], "http") !== FALSE) {
				// doc est un lien
				$isALink = true;
			} else  {
				$isALink = false;
			}
						
			create_docnumExisting($notice['notice_id'], 0, trim($notice['n_regle']), $nomFichier, "pdf", 'application/pdf', 1, $isALink);
			
		}
		setCPNotice($notice['notice_id'], nl2br($notice['n_contenu']), importCPNotice("contenu_jeu", "Contenu", "comment", "text", TRUE), "text");
		
		
		// champ docnotice
		if (! empty($notice['docnotice'])) { // TO TEST notice ID : 71 ludo cocof
			$tmp_v = $notice['docnotice'];
			$nomFichierInfos = mb_pathinfo($tmp_v);
			$nomFichier=$nomFichierInfos['basename'];
			$extension =$nomFichierInfos['extension'];
			$mime = "";
			
			switch ($extension) {
				case "pdf": 
					$mime = "application/pdf";
					break;
				case "doc": 
					$mime = "application/msword";
					break;
				case "jpg":
				case "jpeg":
					$mime = "image/jpeg";				
					break;
				default:
					$mime = "application/pdf";
					break;
			}
			create_docnumExisting($notice['notice_id'], 0, $nomFichier, $nomFichier, $extension, $mime, 1, FALSE); // attention il faut màj le répertoire d'upload spécifique !!!
		}
		
		
		//TODO attente du feed-back du  client !
		//setCPNotice($notice['notice_id'], $notice['prolongation'], importCPNotice("prolongation", "Prolongeable", "text", "integer"), "boolean");
		
		
		// traitement des mots-clés thésaurus
		// on récupère les mots clés et on les relie au thésaurus "mot clef"
		
		// TODO : importer le thésaurus au préalable le thésaurus des mots-clés issus de la cocof
		// Février 2017 : fix thesaurus 
		
				
		$motsCles =  explode(';', $notice['index_l']);
		
		$order = 0;
		foreach ($motsCles as $motCle) {
			$categoryId = importCategoryThesaurus($motCle, THESAURUS_MOT_CLEF, "fr_FR"); // la fonction teste l'existence du thésaurus, le crée si il n'existe pas
			if ($categoryId === FALSE) {
				echo "<br>Erreur de Thésaurus <br>";
				break;
			} else {				
				importCategoryNotice($notice['notice_id'], $categoryId, $order);
				$order++;
			}
		}
	
		
	}
	
	
	// ETAPE 2 : conversion des champs d'exemplaires
	
	$listExemplaires = getAllExemplaires();
	foreach ($listExemplaires as $expl)
	{
		setCPExemplaire($expl['expl_id'], $expl['expl_annee_ed'], importCPExemplaire("annee_edition", "Année d'édition", "text", "integer"), "integer");
		setCPExemplaire($expl['expl_id'], $expl['expl_contenu'], importCPExemplaire("contenu_jeu", "Contenu", "comment", "text"), "text");
		$lastChars = substr($notice['n_regle'], -3);
		if ($lastChars != "pdf" && $lastChars != "PDF")
		{
			setCPExemplaire($expl['expl_id'], $expl['expl_regle'], importCPExemplaire("regle", "Règle", "comment", "text"), "text");
		}
		else
		{
			//@TODO Traitement du pdf
		}
		setCPExemplaire($expl['expl_id'], $expl['expl_mat_accomp'], importCPExemplaire("materiel_accompagnement", "Matériel d'accompagnement", "comment", "text"), "text");
		setCPExemplaire($expl['expl_id'], $expl['expl_fournisseur'], importCPExemplaire("fournisseur", "Fournisseur", "text", "text"), "text");
		setCPExemplaire($expl['expl_id'], $expl['expl_num_fact'], importCPExemplaire("numero_facture", "Numéro de facture", "text", "text"), "text");
		setCPExemplaire($expl['expl_id'], $expl['expl_date_achat'], importCPExemplaire("date_achat", "Date d'achat", "date_box", "date"), "date");
		setCPExemplaire($expl['expl_id'], $expl['expl_date_circu'], importCPExemplaire("date_circulation", "Date de mise en circulation", "date_box", "date"), "date");
		setCPExemplaire($expl['expl_id'], $expl['expl_date_retrait'], importCPExemplaire("date_retrait", "Date de retrait", "date_box", "date"), "date");
		setCPExemplaire($expl['expl_id'], $expl['expl_prix_achat'], importCPExemplaire("prix_achat", "Prix d'achat", "text", "float"), "float");

	}
	
	
	// ETAPE 3 : conversion des champs de lecteurs
	
	
	
	$listEmprunteurs = getAllEmprunteurs();
	
	$emprunteursNewPwd = array();
	
	foreach ($listEmprunteurs as $empr)
	{
		setCPEmprunteur($empr['id_empr'], $empr['empr_adr21'], importCPEmprunteur("adr_2", "Rue", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_adr22'], importCPEmprunteur("adr_22", "Rue (2)", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_cp2'], importCPEmprunteur("cp_2", "C.P.", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_ville2'], importCPEmprunteur("ville_2", "Ville", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_pays2'], importCPEmprunteur("pays_2", "Pays", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_gsm'], importCPEmprunteur("gsm", "GSM", "text", "text"), "text");
		/*setCPEmprunteur($empr['id_empr'], $empr['empr_day'], importCPEmprunteur("birth_day", "Jour de naissance", "text", "text"), "text");
		setCPEmprunteur($empr['id_empr'], $empr['empr_month'], importCPEmprunteur("birth_month", "Mois de naissance", "text", "text"), "text");*/
		if (!empty($empr['empr_year']) && !empty($empr['empr_month']) && !empty($empr['empr_day'])) {
			$birthDate = $empr['empr_year'] . '-' . $empr['empr_month'] . '-' . $empr['empr_day'] ;
			setCPEmprunteur($empr['id_empr'], $birthDate, importCPEmprunteur("birth_date", "Date de naissance", "date_box", "date"), "date");
		}
		
		if (empty($empr['empr_password'])) {
			// pas de mot passe pour ce lecteur
			if (! empty($empr['empr_year']) && strlen($empr['empr_year']) == 4) {
				$pwd = $empr['empr_year'];
			}
			else { 
				$pwd = getRandomPassword(4);
			}
									
			updatePwdEmpr($empr['id_empr'], $pwd);
			
			$empr['empr_password'] = $pwd;
			
			$emprunteursNewPwd[] = $empr;			
			
		}
		
	}
	
	if (count($emprunteursNewPwd) > 0) {
		echo "<br><h1>Lecteurs avec un nouveau mot de passe (emailing à envoyer)</h1>";
		echo "<table border='1'><thead><tr><th>Nom</th><th>Prénom</th><th>E-mail</th><th>Login OPAC</th><th>Mot de passe</th></tr></thead><tbody> ";
		foreach ($emprunteursNewPwd as $empr) {
			echo "<tr>" . "<td>" . $empr['empr_nom'] . "</td>" . "<td>" . $empr['empr_prenom'] . "</td>" . "<td>" . $empr['empr_mail'] . "</td>" . "<td>" . $empr['empr_login'] . "</td>". "<td>" . $empr['empr_password'] . "</td>" . "</tr>";
		}
		echo "</tbody></table>";		
	} else {
		echo "<br><h1>Pas de nouveaux lecteurs avec un nouveau mot de passe</h1>";
	}
	
	
	echo 'FIN DU PATCH ' . $patch_id . '<br>';
	

?>