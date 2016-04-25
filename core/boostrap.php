<?php
/**
 * Razord PHP
 * @author     Jason
 * @copyright  Copyright (c) 2015 razord.ijason.cc
 * @license    http://opensource.org/licenses/MIT   MIT License
 */

class Boostrap
{
    private $globalVerify;

    /**
     * 初始化配置
     */
    public function __construct ($globalVerify = false)
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            self::error(600, 'PHP版本过低');
        }
        set_include_path(get_include_path().PATH_SEPARATOR.'core');
        set_include_path(get_include_path().PATH_SEPARATOR.'controller');
        $this->globalVerify = $globalVerify;
    }

    public function start ($globalVerify)
    {
        if ($globalVerify) {
            if (file_exists(__ROOTDIR__.'/controller/verify.class.php')) {
                require_once __ROOTDIR__.'/controller/verify.class.php';
                $verify = new verify;
                if (!$verify->verify()) {
                    self::error(403, '您没有权限访问。');
                }
            } else {
                self::error(100, '全局验证类未加载。');
            }
        }

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

    public function output($content)
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit();
    }

    private function exec($matches, $path, $method, $className)
    {
        if (!strpos($matches[1][0], ':') && $path['method'] == strtoupper($matches[0][0]) && $path['path'] == $matches[1][0]) {
            $methodName = $method->name;
            $className::$methodName();
            return 1;
        } else if (strpos($matches[1][0], ':')) {
            $queryFromComment = self::getQueryFromComment($matches[1][0]);
            $paths = explode('/', $path['path']);

            if (count($paths) != count(explode('/', $matches[1][0]))) {
                return 0;
            }

            $query = array();
            foreach ($queryFromComment as $key => $queries) {
                $query[$key] = $paths[$queries];
            }

            $methodName = $method->name;
            $className::$methodName($query);
        } else {
            return 0;
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

    /**
     * getPath ()
     * @return string
     */
    private function getPath ()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            if (substr($_SERVER['PATH_INFO'], -1) === '/') {
                $pathInfo = substr($_SERVER['PATH_INFO'], 1, -1);
            } else { 
                $pathInfo = substr($_SERVER['PATH_INFO'], 1);
            }

            $pathInfo = explode('/', $pathInfo);
            $controller = $pathInfo[0];
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
     * error ()
     * @param int $errCode
     * @param str $errMsg
     * @return json
     */
    private function error ($errCode = 0, $errMsg = '')
    {
        $outputTemplate = array('errCode' => $errCode, 'errMsg' => $errMsg);
        self::output($outputTemplate);
    }

}