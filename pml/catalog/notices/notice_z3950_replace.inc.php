<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_z3950_replace.inc.php,v 1.3 2015-04-03 11:16:24 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de remplacement notice par z3950

//verification des droits de modification notice
$acces_m=1;
if ($id_notice!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom= $ac->setDomain(1);
	$usr_prf = $dom->getCurrentUserProfile();
	$pos = $dom->getUserPos($usr_prf);
	$q = "select count(1) from acces_res_1 where res_num = $id_notice and ((ord(mid(res_rights, $pos ,1)) & 16)=16) ";
	$r = mysql_query($q, $dbh);
	if(mysql_result($r,0,0)==0) {
		$acces_m=0;
	}
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {

	if ($z3950_accessible) {
		// menage dans les trucs un peu vieux qui ont ete remontes
		// on delete ce qui est vieux de plus de deux jours.
		$rqt = "select zquery_id from z_query where zquery_date < date_sub(now(),INTERVAL 2 DAY) ";
		$res_zquery=pmb_mysql_query($rqt,$dbh);
		while ($ligne=pmb_mysql_fetch_array($res_zquery)) {
			$zquery_id=$ligne["zquery_id"];
			$rqt_notices = "delete from z_ludotech_notices where z_last_query ='".$zquery_id."' ";
			$res=mysql_query($rqt_notices,$dbh);
			$rqt_notices = "delete from z_notices where znotices_query_id ='".$zquery_id."' ";
			$res=pmb_mysql_query($rqt_notices,$dbh);
			$rqt_query = "delete from z_query where zquery_id ='".$zquery_id."' ";
			$del_znotices=pmb_mysql_query($rqt_query,$dbh);
		}
		include('./catalog/z3950/main.inc.php');
	}

}
?>