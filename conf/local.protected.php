<?php
define('PUN_ROOT', dirname(__FILE__).'/../../forum/');
include PUN_ROOT.'include/common.php';
 
$conf['useacl'] = 1;
$conf['superuser'] = '@Administrateurs';
$conf['authtype'] = 'authfluxbb';
$conf['disableactions'] = 'register,resendpwd,profile,login,logout';