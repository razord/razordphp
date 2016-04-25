# Razord PHP

一款使用为开发者开发Restful php应用而生的框架

A framework for developer to develop a restful php app


## 启动

无全局验证

```php
(new Boostrap)->start();
```

全局验证

```php
(new Boostrap)->start(true);
```

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

#### More

更详细的文档请稍后。