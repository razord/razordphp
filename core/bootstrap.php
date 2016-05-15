<?php
/**
 * Razord PHP
 * @author     Jason
 * @copyright  Copyright (c) 2015 razord.ijason.cc
 * @license    http://opensource.org/licenses/MIT   MIT License
 */

class Bootstrap
{
    public $prefix = '';
    private $modules = array();
    private $moduleList = array();
    private $moduleNameReservedWords = array('bootstrap', 'razord', 'init', 'core', 'config', 'exec');

    /**
     * 初始化配置
     */
    public function __construct ($prefix = '')
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            self::error(101, 'PHP版本过低');
        }
        set_include_path(get_include_path().PATH_SEPARATOR.'core');
        set_include_path(get_include_path().PATH_SEPARATOR.'controller');
        $this->prefix = $prefix;
    }

    /**
     * 程序启动函数
     */
    public function start ()
    {
        $path = self::getPath();

        $className = $path['controller'];

        if (!file_exists(__ROOTDIR__.'/controller/' . $className . '.class.php')) {
            self::error(404, '您访问的页面未找到。');
        }

        $reflection = new ReflectionClass($className);
        $methods = $reflection->getMethods();

        $hasMethods = 0;

        foreach ($methods as $method) {
            $methodComment = $method->getDocComment();
            $matches = self::parseComment($methodComment);

            $hasMethods = self::exec($matches, $path, $method, $className);
            if ($hasMethods === 1) {
                break;
            }
        }

        if ($hasMethods === 0) {
            self::error(404, '您访问的页面未找到。');
        }
    }

    /**
     * 输出函数
     * @param  Any    $content [要输出的内容，可以是任何类型]
     * @return String          [转换成JSON字符串输出]
     */
    public function output ($content)
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit();
    }

    /**
     * 模块加载函数
     * @param  [String|Array] $moduleName [要加载的模块名称，可以是模块列表的数组]
     */
    public function load ($moduleName)
    {
        if (!isset($moduleName)) {
            self::error(102, '模块名称不能为空。');
        }
        if (is_array($moduleName)) {
            foreach ($moduleName as $name) {
                if (!file_exists(__ROOTDIR__.'/module/' . $name . '.class.php')) {
                    self::error(103, '未找到相应模块。');
                }
                if (in_array(strtolower($name), $this->moduleNameReservedWords)) {
                    self::error(104, '模块名称不能使用保留字');
                }
                array_push($this->moduleList, $name);
            }
        } else {
            if (!file_exists(__ROOTDIR__.'/module/' . $moduleName . '.class.php')) {
                self::error(103, '未找到相应模块。');
            }
            if (in_array(strtolower($moduleName), $this->moduleNameReservedWords)) {
                self::error(104, '模块名称不能使用保留字');
            }
            array_push($this->moduleList, $moduleName);
        }
    }

    private function exec ($matches, $path, $method, $className)
    {
        if (!strpos($matches[1][0], ':')
            && $path['method'] == strtoupper($matches[0][0])
            && $path['path'] == $matches[1][0]) {

            self::loadModule();

            $methodName = $method->name;
            $className::$methodName($this->modules);

            return true;
        } else if (strpos($matches[1][0], ':')) {
            $queryFromComment = self::getQueryFromComment($matches[1][0]);
            $paths = explode('/', $path['path']);

            if (count($paths) != count(explode('/', $matches[1][0]))) {
                return false;
            }

            $query = array();
            foreach ($queryFromComment as $key => $queries) {
                $query[$key] = $paths[$queries];
            }

            self::loadModule();

            $methodName = $method->name;
            $className::$methodName($this->modules, $query);
        } else {
            return false;
        }
    }

    private function loadModule ()
    {
        foreach ($this->moduleList as $module) {
            require __ROOTDIR__.'/module/' . $module . '.class.php';
            $this->modules[$module] = new $module;
            $this->modules[$module]->exec();
        }
    }

    private function getQueryFromComment ($comment)
    {
        preg_match_all('/\/\:([^\)\/]*)/is', $comment, $matches);
        $matches = array_splice($matches, 1)[0];

        $comments = explode('/', $comment);
        $indexs = array();
        for ($i = 0; $i < count($comments); $i++) {
            foreach ($matches as $match) {
                $index = strpos($comments[$i], $match);
                if ($index) {
                    $indexs[$match] = $i;
                }
            }
        }

        return $indexs;
    }

    private function parseComment ($comment)
    {
        preg_match_all('/@\s*url\s*\(((?:[a-zA-Z]+))\s*,\s*\'([^\']+)\'\)/is', $comment, $matches);
        return array_splice($matches, 1);
    }

    private function getPath ()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            $path = str_replace('/' . $this->prefix, '', $_SERVER['PATH_INFO']);

            if (substr($path, -1) === '/') {
                $pathInfo = substr($path, 1, -1);
            } else { 
                $pathInfo = substr($path, 1);
            }

            $pathInfo = explode('/', $pathInfo);
            $controller = ($pathInfo[0]) ? $pathInfo[0] : 'index';
            $pathInfo = array_splice($pathInfo, 1);

            if (count($pathInfo) === 0) {
                $path = '/';
            } else {
                $path = '/' . implode('/', $pathInfo);
            }

            $router = array('controller' => $controller, 'path' => $path);

        } else {

            $router = array('controller' => 'index', 'path' => '/');

        }
        $router['method'] = $_SERVER['REQUEST_METHOD'];

        return $router;
    }

    /**
     * 内部错误处理函数
     * @param int    $errCode 错误代码
     * @param String $errMsg  错误提示信息
     */
    private function error ($errCode = 0, $errMsg = '')
    {
        $outputTemplate = array('errCode' => $errCode, 'errMsg' => $errMsg);
        if ($errCode >= 100 && $errCode <= 199) {
            $outputTemplate['Document'] = 'See the document: https://github.com/jas0ncn/razordphp/wiki/%E9%94%99%E8%AF%AF#' . $errCode;
        }
        self::output($outputTemplate);
    }

}