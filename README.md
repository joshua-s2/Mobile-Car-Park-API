##Setup Guide
Ensure that you have PHP >+7.2 MYSQL and  [composer](https://getcomposer.org/)
installed in your machine

* Clone this project
* Copy `.env.exapmle` to `.env` 
* Update `.env` with your MYSQL details
* Run 
```shell script
composer install
```
```shell script
php artisan key:generate
php artisan jwt:secret
php artisan migrate
```

* Start the development server:
```shell script
php artisan serve
```

##API Documentation
