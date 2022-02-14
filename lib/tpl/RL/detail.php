<?php
/**
 * DokuWiki Image Detail Page
 *
 * @author   Andreas Gohr <andi@splitbrain.org>
 * @author   Anika Henke <anika@selfthinker.org>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
header('X-UA-Compatible: IE=edge,chrome=1');

?><!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="utf-8" />
    <title>
        <?php echo hsc(tpl_img_getTag('IPTC.Headline',$IMG))?>
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

	<link rel="stylesheet" type="text/css" href="<?php echo path_to_forum.'style/Global/global.min.css?version=' . current_theme . '' ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo path_to_forum.'style/'.RLStyle($pun_user['style']).'.css?version=' . current_theme . '' ?>" id="MyCss" />
	<link rel="stylesheet" type="text/css" href="<?php echo path_to_rl ?>tpl/fonts/fork-awesome/style.css?version=<?php echo current_theme ?>" />

	<?php
	GetRLStyle();
	// END MODIF RL
	?>
</head>
<body>

	<!-- BEGIN HEADER RL -->
	<?php include PUN_ROOT.'include/user/header.php';?>
	<?php include PUN_ROOT.'include/user/menuG.php';?>
	<!-- END HEADER RL -->
    <div id="dokuwiki__site"><div id="dokuwiki__top" class="site <?php echo tpl_classes(); ?>">

        <?php include('tpl_header.php') ?>

        <div class="wrapper group" id="dokuwiki__detail">

            <!-- ********** CONTENT ********** -->
            <div id="dokuwiki__content"><div class="pad group">
                <?php html_msgarea() ?>

                <?php if(!$ERROR): ?>
                    <div class="pageId"><span><?php echo hsc(tpl_img_getTag('IPTC.Headline',$IMG)); ?></span></div>
                <?php endif; ?>

                <div class="page group">
                    <?php tpl_flush() ?>
                    <?php tpl_includeFile('pageheader.html') ?>
                    <!-- detail start -->
                    <?php
                    if($ERROR):
                        echo '<h1>'.$ERROR.'</h1>';
                    else: ?>
                        <?php if($REV) echo p_locale_xhtml('showrev');?>
                        <h1><?php echo nl2br(hsc(tpl_img_getTag('simple.title'))); ?></h1>

                        <?php tpl_img(900,700); /* parameters: maximum width, maximum height (and more) */ ?>

                        <div class="img_detail">
                            <?php tpl_img_meta(); ?>
                            <dl>
                            <?php
                            echo '<dt>'.$lang['reference'].':</dt>';
                            $media_usage = ft_mediause($IMG,true);
                            if(count($media_usage) > 0){
                                foreach($media_usage as $path){
                                    echo '<dd>'.html_wikilink($path).'</dd>';
                                }
                            }else{
                                echo '<dd>'.$lang['nothingfound'].'</dd>';
                            }
                            ?>
                            </dl>
                            <p><?php echo $lang['media_acl_warning']; ?></p>
                        </div>
                        <?php //Comment in for Debug// dbg(tpl_img_getTag('Simple.Raw'));?>
                    <?php endif; ?>
                </div>
                <!-- detail stop -->
                <?php tpl_includeFile('pagefooter.html') ?>
                <?php tpl_flush() ?>

                <?php /* doesn't make sense like this; @todo: maybe add tpl_imginfo()?
                <div class="docInfo"><?php tpl_pageinfo(); ?></div>
                */ ?>

            </div></div><!-- /content -->

            <hr class="a11y" />

            <!-- PAGE ACTIONS -->
            <?php if (!$ERROR): ?>
                <div id="dokuwiki__pagetools">
                    <h3 class="a11y"><?php echo $lang['page_tools']; ?></h3>
                    <div class="tools">
                        <ul>
                            <?php echo (new \dokuwiki\Menu\DetailMenu())->getListItems(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /wrapper -->

        <?php include('tpl_footer.php') ?>
    </div></div><!-- /site -->
	<?php include PUN_ROOT.'include/user/footer.php';?>
</body>
</html>
