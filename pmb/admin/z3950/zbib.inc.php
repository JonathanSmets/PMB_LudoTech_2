<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: zbib.inc.php,v 1.12 2015-04-03 11:16:27 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des attributs de recherche z3950
?>

<script type="text/javascript">
function test_form(form)
{
	if( (form.form_nom.value.length == 0) || (form.form_base.value.length == 0) || (form.form_url.value.length == 0) || (form.form_format.value.length == 0) || (form.form_search_type.value.length == 0) || (form.form_port.value.length == 0) ) {
		alert("<?php echo $msg['zbib_renseign_valeurs'] ?>");
		return false;
		}
	if(isNaN(form.form_port.value))
	{
		alert("<?php echo $msg['zbib_error_port_no_num'] ?>");
		return false;
	}

	return true;
}
// Now use the javascript confirmation_delete function - Marco Vaninetti
// function confirm_delete(bib_id)
//     {
//         result = confirm("confirmez-vous la suppression de ce serveur ?");
//         if(result)
//             document.location = "./admin.php?categ=z3950&sub=zbib&action=del&id="+bib_id;
//     }

</script>

<?php
function show_zbib($dbh)
{
	global $msg;

	print "<table>
		<tr>
		<th class='titre_data'>$msg[zbib_nom]</th>
		<th class='titre_data'>$msg[zbib_base]</th>
		<!--<th class='titre_data'>$msg[zbib_utilisation]</th>
		<th class='titre_data'>$msg[zbib_nb_attr]</th>-->
		</tr>";

	// affichage du tableau des z_bib
	//$requete = "SELECT bib_id, bib_nom, base, search_type, count(*) as nb_attr FROM z_bib left outer join z_attr on bib_id=attr_bib_id group by bib_id, bib_nom, base, search_type ORDER BY bib_nom, base, search_type ";
    // (mdarville) recherche dans nootre base de données les informations sur les ludotech enregistrées
    $requete = 'SELECT id_ludotech, ip_ludotech, libelle_ludotech FROM z_ludotech ORDER BY libelle_Ludotech';
    $res = pmb_mysql_query($requete, $dbh);

	$nbr = pmb_mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=pmb_mysql_fetch_object($res);
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=z3950&sub=zbib&action=modif&id=$row->id_ludotech';\" ";
				print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					print "<td><strong>".$row->libelle_ludotech."</strong></td>";
					print "<td>".$row->ip_ludotech."</td>";
		//print "<td>".$row->search_type."</td>";
		//print "<td>".$row->nb_attr."</td>";
		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value='".$msg["ajouter"]."' onClick=\"document.location='./admin.php?categ=z3950&sub=zbib&action=add'\" />
		";
		
	}

function zbib_form($znom="", $zbase="", $zsearch_type="CATALOG", $zurl="", $zport="211", $zid=0) {
	global $msg;
	global $admin_zbib_form;


	$admin_zbib_form = str_replace('!!id!!', $zid, $admin_zbib_form);

	if(!$zid) $admin_zbib_form = str_replace('!!form_title!!', $msg["zbib_ajouter_serveur"], $admin_zbib_form);
		else $admin_zbib_form = str_replace('!!form_title!!',$msg["zbib_modifier_serveur"], $admin_zbib_form);

	$admin_zbib_form = str_replace('!!nom!!',         $znom,         $admin_zbib_form);
	$admin_zbib_form = str_replace('!!base!!',        $zbase,        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!search_type!!', $zsearch_type, $admin_zbib_form);
	$admin_zbib_form = str_replace('!!url!!',         $zurl,         $admin_zbib_form);
	$admin_zbib_form = str_replace('!!port!!',        "",        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!nomAddslashes!!',         addslashes($znom),         $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!format!!',      $zformat,      $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!user!!',        $zuser,        $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!password!!',    $zpassword,    $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!sutrs!!',  	  $zsutrs,       $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!zfunc!!',  	  $zfunc,        $admin_zbib_form);
	//$admin_zbib_form = str_replace('!!nom_script!!',  	  addslashes($znom),        $admin_zbib_form);
	
	// added by Marco Vaninetti
	print confirmation_delete("./admin.php?categ=z3950&sub=zbib&action=del&id=");
	// end
	print $admin_zbib_form;
	}

switch($action) {
	case 'update':
	// no duplication
    /* (mdarville)
     * Verification que l'information que l'on veut insérer n'existe pas déjà
     * dans la base de données.
     * Si elle existe --> update sinon insert.
     * Normalement, on ne devrait jamais devoir faire d'insert ici.
     */
	$requete = " SELECT count(1) FROM z_ludotech WHERE (libelle_ludotech='$form_nom' AND id_ludotech!='$id' )  LIMIT 1 ";
	$res = pmb_mysql_query($requete, $dbh);
	$nbr = pmb_mysql_result($res, 0, 0);
	if ($nbr > 0) {
			error_form_message($form_nom.$msg["docs_label_already_used"]);
	} else {
		// O.k., now if the id already exist UPDATE else INSERT
			if(!empty($form_nom) && !empty($form_base) && !empty($form_url) && !empty($form_user) && !empty($form_password)) {
		        // rentre bien ici
					if($id) {
		                $requete = "UPDATE z_ludotech SET libelle_ludotech='$form_nom', nameDB_ludotech='$form_base',ip_ludotech='$form_url',user_ludotech='$form_user',pwd_ludotech='$form_password' WHERE id_ludotech=$id";
						$res = mysql_query($requete, $dbh);
					} else {
						$requete = "INSERT INTO z_ludotech (libelle_ludotech, ip_ludotech, nameDB_ludotech, user_ludotech, pwd_ludotech) VALUES ('$form_nom', '$form_url', '$form_base', '$form_user', '$form_password') ";
						$res = mysql_query($requete, $dbh);
						$id_insert=mysql_insert_id();
		            /*
						$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'sujet', '21') ";
						$res = mysql_query($requete, $dbh);
		            */
						$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'tit1', '1003') ";
						$res = mysql_query($requete, $dbh);
		             /*
						$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'isbn', '7') ";
						$res = mysql_query($requete, $dbh);
		             */
						$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'tit2', '4') ";
						$res = mysql_query($requete, $dbh);
		             
					}
				}
			}
		
		show_zbib($dbh);
		break;
	case 'add':
		/* (mdarville)
		 * Si une des zones de la forme (nom,nom de la base de données, url, user et password
		 * n'est pas remplie, alors on affiche la forme.
		 */
		if(empty($form_nom) || empty($form_base) || empty($form_url) || empty($form_user) || empty($form_password)) {
			zbib_form($form_nom, $form_base, $form_search_type, $form_url, $form_port, $form_format, $form_user, $form_password, $form_sutrs, $form_zfunc);
		} else {
			show_bib($dbh);
		}
		break;
	case 'modif':
		if($id){
			//$requete = "SELECT bib_id, bib_nom, base, search_type, url, port, format, auth_user, auth_pass, sutrs_lang, fichier_func FROM z_bib WHERE bib_id=$id ";
            /* (mdarville)
             * Ici, on va rechercher les données que l'on va modifié et les insérer dans la form.
             */
            $requete = "SELECT id_ludotech, ip_ludotech, libelle_ludotech, nameDB_ludotech, user_ludotech, pwd_ludotech FROM z_ludotech WHERE id_ludotech=$id";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
                zbib_form($row->libelle_ludotech, $row->nameDB_ludotech, 'CATALOG', $row->ip_ludotech,  '', $id);                
			} else {
				show_zbib($dbh);
			}
		} else {
			show_zbib($dbh);
		}
		break;
	case 'del':
		if($id) {
            /* (mdarville).
             * Suppression sur base de l'id.
             */
			$requete = "DELETE FROM z_ludotech WHERE id_ludotech=$id ";
			$res = mysql_query($requete, $dbh);
			$requete = "DELETE FROM z_attr WHERE attr_bib_id=$id ";
			$res = mysql_query($requete, $dbh);
			show_zbib($dbh);
			} else show_zbib($dbh);
		break;
	default:
		show_zbib($dbh);
		break;
}
print "</td></tr></table>";

