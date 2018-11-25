
## Laravel-BI

###安装方法

代码下载后

```bash
composer install
php artisan migrate
php artisan vendor:publish
```

欢迎界面是http://localhost

屏蔽欢迎界面方式为：
```php
注释掉Route::get('/', function () {return view('welcome');});
并增加Route::get('/', function () {return redirect('/home');});
```

前端访问地址为http://localhost/home

后端访问地址为http://localhost/frame
