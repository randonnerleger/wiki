<?php
/**
 * DokuWiki Media Manager Popup
 *
 * @author   Andreas Gohr <andi@splitbrain.org>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
header('X-UA-Compatible: IE=edge,chrome=1');

?><!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction'] ?>" class="popup no-js">
<head>
    <meta charset="utf-8" />
    <title>
        <?php echo hsc($lang['mediaselect'])?>
        [<?php echo strip_tags($conf['title'])?>]
    </title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
	<?php
	tpl_metaheaders();
	// MODIF RL
	// OPITUX
	// Sup des favicons appelés par Dokuwiki, on appelle les notre juste en dessous
	// echo tpl_favicon(array('favicon', 'mobile'))
 	tpl_includeFile('meta.html');

	// MODIF RL
	// OPITUX
	// GESTION DES THEMES, DE LA VERSION ET DU SWITCH CSS

	include PUN_ROOT.'include/user/header_favicon.php';
	include PUN_ROOT.'include/user/header_img_aleatoire.php';
	?>

	<link rel="stylesheet" type="text/css" href="<?php echo path_to_forum.'style/Global/global.css?version=' . current_theme . '' ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo path_to_forum.'style/'.RLStyle($pun_user['style']).'.css?version=' . current_theme . '' ?>" id="MyCss" />

	<?php
	GetRLStyle();
	// END MODIF RL
	?>
</head>

<body>
    <div id="media__manager" class="dokuwiki">
        <?php html_msgarea() ?>
        <div id="mediamgr__aside"><div class="pad">
            <h1><?php echo hsc($lang['mediaselect'])?></h1>

            <?php /* keep the id! additional elements are inserted via JS here */?>
            <div id="media__opts"></div>

            <?php tpl_mediaTree() ?>
        </div></div>

        <div id="mediamgr__content"><div class="pad">
            <?php tpl_mediaContent() ?>
        </div></div>
    </div>
</body>
</html>
