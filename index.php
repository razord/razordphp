<?php
/**
 * PHP RESTful
 * @author     Jason
 * @copyright  Copyright (c) 2015 ijason.cc
 * @license	   http://opensource.org/licenses/MIT	MIT License
 */

/** 加载主文件 */
require('./config.php');
require('./core/init.php');

/** 初始化框架 */
(new Boostrap)->start(true);
?>