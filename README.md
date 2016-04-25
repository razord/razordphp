<img src="http://blog.ijason.cc/static/razordphp/razordphp_logo.jpg" alt="RazordPHP_LOGO" width="400px" />

一款使用为开发者开发Restful php应用而生的框架

A framework for developer to develop a restful php app

## 路由

Razord采用注释来规定路由

举例：

访问路径：`http://localhost/api/bb`

`./controller/api.class.php`：
```php
class api
{
    /**
     * 根目录
     * @url(GET, '/bb')
     * Razord将会解析以上注释，当访问的pathinfo符合时，执行此函数
     */
    public function index () {
        $msg = 'Hello World';
        Boostrap::output($msg);
    }
}
```

将会输出`"Hello World"`。

#### 文档

[文档](https://github.com/jas0ncn/razordphp/wiki/%E4%BB%8B%E7%BB%8D)