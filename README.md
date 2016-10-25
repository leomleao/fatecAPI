# Slim 3 Very simple REST Skeleton

This is a simple skeleton project for Slim 3 that implements a simple REST API.
Based on [https://github.com/moritz-h/slim3-rest-skeleton] who is based on [akrabat's slim3-skeleton](https://github.com/akrabat/slim3-skeleton)

## Purpose

Many micro web frameworks are not that micro, 19 Mb is not a micro framework. Slim provides a low footprint and fast web framework in about 1,5 Mb.

Although Slim gives you the flexibility to organize as you like. I saw a need to organize some basic structures and code for a RestFul API.

**Words of Caution**: this techniques are just my experience and nothing of it has been sanctioned or approved by anyone. Use at your own discretion. 

Take your time to understand how Slim works. http://www.slimframework.com/docs

## Main specs

- Specially oriented to develop Restful APIs using JSON
- Reusable generic Controller and Database access with common CRUD operations
- No need to define models, database columns for simple database access
- Supports ordering the resource list /books?order=price
- Table name given by the resource name / user defined
- Best practices in HTTP return codes
- API Rate limiter middleware, for throttling excesive requests
- oAuth2 from https://github.com/bshaffer/oauth2-server-php

## Install

To explain better.

**1 API Rate limiter install**

First , we create the table xrequests where all incoming requests are registered

```sql
CREATE TABLE IF NOT EXISTS `xrequests` (
  `id` int(11) NOT NULL,
  `originip` varchar(45) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COMMENT='Requests from remote IPs';

ALTER TABLE `xrequests`
 ADD PRIMARY KEY (`id`), ADD KEY `ts` (`ts`), ADD KEY `originip` (`originip`);

ALTER TABLE `xrequests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
```
Then we define the requests and in how many minutes they will be stopped, in **settings.php**

```php
// api rate limiter settings
'api_rate_limiter' => [
    'requests' => '200',
    'inmins' => '60',
],
```
Read more about API Rate Limiter, here https://github.com/pabloroca/slim3-apiratelimit-middleware

## Steps for any new resource/table

**1 Create table**

```sql
CREATE TABLE IF NOT EXISTS `books` (
`id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;


ALTER TABLE `books`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
```

**2 Add the routes (routes.php)**, add the controller to the resources. Where _Controller is the generic CRUD controller

```php
// Books controller
$app->group('/books', function () {
    $this->get   ('',             _Controller::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _Controller::class.':get');
    $this->post  ('',             _Controller::class.':add');
    $this->put   ('/{id:[0-9]+}', _Controller::class.':update');
    $this->delete('/{id:[0-9]+}', _Controller::class.':delete');
});
```

**3 Prepare the Dependencies (dependencies.php)**

If you just want to use the basic CRUD. Nothing to do here! 

If you want to change Controller / DatabaseAccess add this:

At the beginning:

```php
use App\Controllers\MyCustomController;
use App\DataAccess\MyCustomDataAccess;
```

at the end:

```php
// Custom Controllers / DataAccess
$container['App\Controllers\MyCustomController'] = function ($c) {
    return new MyCustomController($c->get('logger'), '', $c->get('App\DataAccess\MyCustomDataAccess'));
};

$container['App\DataAccess\MyCustomDataAccess'] = function ($c) {
    return new MyCustomDataAccess($c->get('logger'), $c->get('pdo'), '');
};
```

## Database table for resource

The database table can be defined in some ways

- automatically by matching with the resource name (using generic _DataAccess as is)
- by the middleware, assigning settings => localtable. Modify **routes.php** and add a group middleware for the resource:

```php
// Books controller
$app->group('/books', function () {
    $this->get   ('',             _Controller::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _Controller::class.':get');
    $this->post  ('',             _Controller::class.':add');
    $this->put   ('/{id:[0-9]+}', _Controller::class.':update');
    $this->delete('/{id:[0-9]+}', _Controller::class.':delete');
})->add(function ($request, $response, $next) {
    $this->settings['localtable'] = “OtherTable”;
    $response = $next($request, $response);
    return $response;
});
```
- create your own DataAccess class in **dependencies.php**. The third parameter is the table name (if empty takes resource name as the table):

```php
$container['App\DataAccess\MyCustomDataAccess'] = function ($c) {
    return new MyCustomDataAccess($c->get('logger'), $c->get('pdo'), ‘OtherTable’);
};
```

## API Rate Limiter

Taken from https://github.com/pabloroca/slim3-apiratelimit-middleware but with a better integration

## oAuth2 authentication

Using https://github.com/bshaffer/oauth2-server-php. This library is one of the most lightweighted and with proper documentation here: http://bshaffer.github.io/oauth2-server-php-docs. The main issue with this library is that uses HTTPFoundation so I needed to convert requests to PSR7 format.

For now implemented this grants (in **dependencies.php**) as an oAuth container:

* Resource Owner Password Credentials
* Client Credentials

**Additional step required for oAuth**

Create the tables who holds oAuth logic, I have implemented a MySQL/mariaDB database

```SQL
CREATE TABLE oauth_clients (client_id VARCHAR(80) NOT NULL, client_secret VARCHAR(80), redirect_uri VARCHAR(2000) NOT NULL, grant_types VARCHAR(80), scope VARCHAR(100), user_id VARCHAR(80), CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));
CREATE TABLE oauth_access_tokens (access_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT access_token_pk PRIMARY KEY (access_token));
CREATE TABLE oauth_authorization_codes (authorization_code VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), redirect_uri VARCHAR(2000), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));
CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));
CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN);
CREATE TABLE oauth_jwt (client_id VARCHAR(80) NOT NULL, subject VARCHAR(80), public_key VARCHAR(2000), CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));
```
We are going to use our own user table, so we create a table like this:

```SQL
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1;

ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD KEY `email` (`email`);
```
For using a custom user table, I should create **_oAuth2_CustomStorage.php** who binds data fro this user table to oAuth2

For using oAuth in a resource, you must use _Controller_oAuth2 or extend it. See **routes.php**

This resource is for retrieving a token (Resource Owner Password Credentials or Client Credentials)
```php
// oAuth2
$app->group('/oauth', function () {
    $this->post('/token', _oAuth2TokenController::class.':token');
});
```

resources who needs oAuth2 authentication
```php
// Books controller
$app->group('/books', function () {
    $this->get   ('',             _Controller_oAuth2::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _Controller_oAuth2::class.':get');
    $this->post  ('',             _Controller_oAuth2::class.':add');
    $this->put   ('/{id:[0-9]+}', _Controller_oAuth2::class.':update');
    $this->delete('/{id:[0-9]+}', _Controller_oAuth2::class.':delete');
});
```

Grant can be Resource Owner Password Credentials or Client Credentials, inspect **_Controller_oAuth2.php** and you will see that **getAll** and **get** just need Client Credentials grant and the others needs Resource Owner Password Credentials grants.

**Calling oAuth**

It's recommended to understand how oAuth2 works. But in short:

- you get a token
- you request the resource with that token
- when token expires (receiving HTTP 401 status), you start over again

**Getting a Client Credentials token**

```
request: https://apy.mydomain.com/oauth/token
Method: POST
Request headers send:
Content-Type: application/x-www-form-urlencoded
Body send:
client_id=MYCLIENTD&client_secret=MYCLIENTSECRET&grant_type=client_credentials
```

Where MYCLIENTD is column client_id and MYCLIENTSECRET is column client_secret from table oauth_clients

**Getting a Resource Owner Password Credentials token**

```
request: https://apy.mydomain.com/oauth/token
Method: POST
Request headers send:
Content-Type: application/x-www-form-urlencoded
Body send:
client_id=MYCLIENTD&client_secret=MYCLIENTSECRET&grant_type=password&username=USEREMAIL&password=USERPASSWORD
```

Where USEREMAIL is column email and USERPASSWORD is column password from table users. The password is encoded with SHA1 in the table

**Accesing a resource with a token**

```
request: https://apy.mydomain.com/books
Request headers send:
Content-Type: application/json
Authorization: Bearer MYTOKEN
```


