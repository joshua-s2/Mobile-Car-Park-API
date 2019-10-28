## Setup Guide
Ensure that you have PHP >+7.2 MYSQL and  [composer](https://getcomposer.org/)
installed in your machine

* Clone this project
* Copy `.env.exapmle` to `.env` 
* Update `.env` with your MYSQL details
* Run 
```
composer install
```
```
php artisan key:generate
php artisan jwt:secret
php artisan migrate
```

* Start the development server:
```
php artisan serve
```

## API Documentation

[API Documentation](https://documenter.getpostman.com/view/8265642/SVzxXzNj?version=latest)
