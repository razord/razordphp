<?php
/** 程序根目录 */
define('__ROOTDIR__', __DIR__);

/** PDO Config */
$_pdo = array();

$_pdo['dbtype'] = 'mysql';

if (!defined('SAE_MYSQL_DB')) {

	$_pdo['dbname'] = '';               // 数据库名
	$_pdo['host'] = '127.0.0.1';        // 数据主机地址
	$_pdo['port'] = 3306;               // 数据库端口
	$_pdo['username'] = 'root';         // 数据库用户名
	$_pdo['password'] = '';             // 数据库密码

} else {

	$_pdo['dbname'] = SAE_MYSQL_DB;
	$_pdo['host'] = SAE_MYSQL_HOST_M;
	$_pdo['port'] = SAE_MYSQL_PORT;
	$_pdo['username'] = SAE_MYSQL_USER;
	$_pdo['password'] = SAE_MYSQL_PASS;

}

$_pdo['charset'] = 'utf-8';             // 数据库编码

?>