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
    public function root ($modules) {
        $msg = 'Hello World';
        Bootstrap::output($msg);
    }

    /**
     * 二级目录
     * @url(GET, '/heyjason')
     */
    public function heyJason ($modules) {
        $msg = 'Hey Jason!';
        Bootstrap::output($msg);
    }

    /**
     * 二级目录带参数
     * @url(GET, '/heyjason/:keyword')
     */
    public function heyJasonWithKeyword ($modules, $query) {
        Bootstrap::output($query);
    }

    /**
     * 同个二级目录带多个参数
     * @url(GET, '/heyjason/:keyword1/:keyword2')
     */
    public function heyJasonWithKeywords ($modules, $query) {
        Bootstrap::output($query);
    }

    /**
     * 调用模块
     * @url(GET, '/module')
     */
    public function module ($modules) {
        $msg = $modules['verify']->getMsg();
        Bootstrap::output($msg);
    }
}

?>