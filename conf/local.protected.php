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

// Config Forum utilisées dans le menu gauche
$conf['pun_style'] = $pun_user['style'];
$conf['group_id'] = $pun_user['group_id'];
$conf['id'] = $pun_user['id'];

// Redirection temporaire si forum en maintenance
if ($pun_config['o_maintenance'] == '1' &&  $pun_user['group_id'] != 1) {
	header('HTTP/1.1 503 Service Unavailable');

	// Send no-cache headers
	header('Expires: Thu, 21 Jul 1977 07:30:00 GMT'); // When yours truly first set eyes on this world! :)
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache'); // For HTTP/1.0 compatibility

	// Send the Content-type header in case the web server is setup to send something else
	header('Content-type: text/html; charset=utf-8');
	require_once( RL_ROOT . '../maintenance.php');
	exit;
}
// Fin de la Redirection temporaire
