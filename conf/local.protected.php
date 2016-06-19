<?php
define('RL_ROOT', dirname(__FILE__).'../../');
include RL_ROOT.'../configRL.php';
define('PUN_ROOT', dirname(__FILE__).'/../../'.folder_forum.'/');
include PUN_ROOT.'include/common.php';
define('RL_STYLE', $pun_user['style']);						# On appel de Style du profil FLUXBB

$conf['useacl'] = 1;
$conf['superuser'] = '@Administrateurs';
$conf['authtype'] = 'authfluxbb';
$conf['disableactions'] = 'register,resendpwd,profile,login,logout';

// Config Forum utilisés dans le menu gauche
$conf['pun_style'] = $pun_user['style'];
$conf['group_id'] = $pun_user['group_id'];
$conf['id'] = $pun_user['id'];

		// Redirection temporaire si forum en maintenance
		if ($pun_config['o_maintenance'] == '1') {
			header('Location: ' . path_to_forum . 'index.php', true, 302);
			// OR: header('Location: ' . path_to_forum . 'index.php');
			// OR: header('Location: ' . path_to_forum . 'index.php', true, 302);
			exit;
		}
		// Fin de la Redirection temporaire

