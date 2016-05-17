<?php
/**
 * Razord PHP
 * @author     Jason
 * @copyright  Copyright (c) 2015 razord.ijason.cc
 * @license    http://opensource.org/licenses/MIT	MIT License
 */

/* 加载主文件 */
require('./config.php');
require('./core/init.php');

/* 实例化应用 */
$Razord = new Bootstrap('api');

/* 加载模块 */
$moduleNames = array('verify'); // 要加载的模块名称的数组
$Razord->load($moduleNames);

/* 启动框架 */
$Razord->start();
?>