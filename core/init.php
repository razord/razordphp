<?php
/**
 * Razord PHP
 * @author     Jason
 * @copyright  Copyright (c) 2015 razord.ijason.cc
 * @license	   http://opensource.org/licenses/MIT	MIT License
 */

set_include_path(get_include_path().PATH_SEPARATOR.'core');
spl_autoload_extensions('.php,.class.php');
spl_autoload_register();