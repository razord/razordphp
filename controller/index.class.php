<?php
/**
 * Razord PHP
 * @author     Jason
 * @copyright  Copyright (c) 2015 razord.ijason.cc
 * @license    http://opensource.org/licenses/MIT   MIT License
 */

/**
 * Demo class
 */
class index
{
    /**
     * 根目录
     * @url(GET, '/')
     */
    public function index () {
        $msg = 'Hello World';
        Boostrap::output($msg);
    }

    /**
     * 二级目录
     * @url(GET, '/heyjason')
     */
    public function directoryJason () {
        $msg = 'Hey Jason!';
        Boostrap::output($msg);
    }

    /**
     * 二级目录带参数
     * @url(GET, '/heyjason/:keyword')
     */
    public function directoryJasonHasOneQuery ($query) {
        Boostrap::output($query);
    }

    /**
     * 同个二级目录带多个参数
     * @url(GET, '/heyjason/:keyword1/:keyword2')
     */
    public function directoryJasonHasQueries ($query) {
        Boostrap::output($query);
    }
}

?>