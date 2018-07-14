# MiniFast-Framework
A mini and fast PHP Framework. Works best with MiniFast-ORM.

# Documentation

## Installation

Install MiniFast with [Composer](https://getcomposer.org/):
```sh
$ wget http://getcomposer.org/composer.phar ; php composer.phar require itechcydia/minifast ^1
```

Then you just have to include Composer `vendor/autoload.php` file.

## How do I use it?

### Routeur - Friendly URL

Create a simple .htaccess file on your root directory if you're using Apache with mod_rewrite enabled.
```apache
AddDefaultCharset utf-8
Options -MultiViews
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
RewriteBase /
```

If you're using nginx, setup your server section as following:
```nginx
server {
	listen 80;
	server_name mydevsite.dev;
	root /var/www/mydevsite/public;

	index index.php;

	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		# NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

		# With php5-fpm:
		fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi.conf;
		fastcgi_intercept_errors on;
	}
}
```

#### Manuel way
It is very simple to use. You can get the URL as a PHP array without $_GET parameters:
```php
<?php
// Example URL: https://server.com/user/23
include __DIR__ . '/vendor/autoload.php';

$route = new Route();
$route = $route->getRouteAsArray(); // Will return array(2) { [0]=> string(4) "user", [1]=> string(2) "23" }
```

And with this array you will do your routing system like this:
```php
<?php
if(sizeof($route) == 0) // This is for the index
{
    // Your code
}
elseif($route[0] == 'user' and sizeof($route) > 1) // For the profile page
{
    // $route[1] will have the user ID
}
// ...
```

#### Automatic way
If you don't want to manage the route manualy, MiniFast can do it for you. You will have to write your routes in a JSON file:
```json
{
    "routes": [
        {
            "route": "/",
            "view": "index"
        },
        {
            "route": "/user/{pseudo}",
            "controller": "UserController"
        }
    ]
}
```
If you don"t have controller for a route, don't specify it or set its value to null.
Variable in the URL is written between accolades '{' and '}'.
The view can be choosen inside the controller so in this case, like the controller, don't specify it or set its value to null.

With this automatique methode, your `index.php` will be very simple:
```php
<?php
session_start();    // You need to start sessions because
                    // vars in the URL will be stored in
                    // $_SESSION['route']
$route = new Route();
$route->fromFile(
    __DIR__ . '/routes.json',
    __DIR__ . '/controllers',
    __DIR__ . '/templates'
);
```

### View

#### Twig

MiniFast is using [Twig](https://twig.symfony.com/doc/2.x/) as template manager.
For this part, you will need to refere on the [Twig](https://twig.symfony.com/doc/2.x/) documentation. No need to install it as it's already included in MiniFast when installed via Composer.

#### Using

The first thing to do is to specify the template directory:
```php
<?php
$view = new View(__DIR__ . '/path/to/templates');
```

Then when you want to render a specific template:
```php
<?php
// Your code...
elseif($route[0] == 'downloads')
{
    // No need to write '.twig' at the end of the template name, MiniFast do it for you
    $view->render('template_name', [
        'var1' => $var1,
        'var2' => $var2
    ]);
}
// Your code...
```

You can also use the `View` class inside a controller, see below.

### Controller

It is usefull to use a controller to do some specific operations outside your `index.php`. Like the view, you need to specify the controllers directory:
```php
<?php
$controller = new Controller(__DIR__ . '/path/to/controllers');
```

The controller will scan recursivly the controllers directory to find all files that end with `Controller.php`. For example, your controller for a login should be `loginController.php`. The controller will store controllers with a unique name based on the subdirectory where they are. 

Imagine you have the following web root:
```text
`- web
   `- controllers
   |  `- user
   |  |  +- loginController.php
   |  |  +- registerController.php
   |  `- request
   |  |  +- someController.php
   `- templates
   `- vendor
   +- .htaccess
   +- index.php
```

The `loginController.php` file will be name `UserLogin` because it is in the `user` directory.
Now you want to use this controller, do it this way:
```php
<?php
// Your code...
elseif($route[1] == 'login')
{
    $controller->useController('UserLogin');
}
// Your code...
```

And that's it, `loginController.php` is invoked and executed. But you may need to pass variables to the controller. To do so, you can add a second argument with a multidimentional array:
```php
<?php
// Your code...
elseif($route[1] == 'login')
{
    $controller->useController('UserLogin', [
        'var1' $var1
    ]);
}
// Your code...
```

Then in your controller, you can retrive it in `$_SESSION['route']['var1']`.

#### Hope you will enjoy MiniFast !
