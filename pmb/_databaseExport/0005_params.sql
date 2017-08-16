-- **************************************************************************************
-- Script: 0005
-- Description: paramètres divers
-- Date: 21/06/2016
-- Author: FFI

-- set script vars

CREATE TABLE IF NOT EXISTS dbmigrationlog (
  ScriptNumber varchar(10) NOT NULL,
  ScriptComment varchar(255) NOT NULL
);

INSERT INTO dbmigrationlog VALUES ('0005', 'Parametres Divers');


UPDATE parametres SET valeur_param = "1 2" WHERE  type_param = "opac" and sstype_param="show_categ_browser" ;

UPDATE parametres SET valeur_param = "1" WHERE  type_param = "pmb" and sstype_param="book_pics_show" ;

UPDATE parametres SET valeur_param = "func_ludotheques.inc.php" WHERE  type_param = "z3950" and sstype_param="import_modele" ;

UPDATE parametres SET valeur_param = "2" WHERE  type_param = "pmb" and sstype_param="notice_img_folder_id" ;

UPDATE notices_custom SET multiple = "1" WHERE notices_custom.idchamp = 7;

UPDATE users SET deflt_upload_repertoire = '1';

UPDATE users SET deflt_styles = 'couleurs_onglets';

-- BGA: changed this so that it "ludo" but it's a fake one thas wasn't used in any test or prod env
update users set pwd = "*59566802E6B1616DCEC08935FC0036EE04229EA8";

UPDATE parametres SET valeur_param = "<script type=\"text/javascript\" src=\"./tinymce/tinymce.min.js\"></script>
<script type=\"text/javascript\">
tinymce.init({
selector : \"textarea#contenu_jeu_0\",
theme : \"modern\",
convert_urls : false,
relative_urls: false,
language : \"fr_FR\",
extended_valid_elements : \"hr[class|width|size|noshade],a[name|href|target|title|onclick|rel],font[face|size|color|style],span[class|align|style],p[lang],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]\",
plugins :
\"advlist,autolink,contextmenu,directionality,image,emoticons,fullscreen,insertdatetime,link,lists,layer,media,nonbreaking,noneditable,paste,preview,print,save,searchreplace,importcss,table,textcolor,visualchars,code\",
toolbar: \"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons\",
image_advtab: true,
insertdatetime_dateformat: \"%Y-%m-%d\",
insertdatetime_timeformat: \"%H:%M:%S\"
});
</script>" where type_param = "pmb" and sstype_param="javascript_office_editor" ;


UPDATE parametres SET valeur_param = "0" WHERE  type_param = "finance" and sstype_param="blocage_amende" ;
UPDATE parametres SET valeur_param = "1" WHERE  type_param = "pmb" and sstype_param="expl_show_dates" ;
UPDATE parametres SET valeur_param = "0" WHERE  type_param = "pmb" and sstype_param="docnum_in_database_allow" ;


