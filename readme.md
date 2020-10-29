# Laravel 

[Laravel office website](https://laravel.tw/)

## install composer 

- Composer version 2.0.3 2020-10-28 15:50:55

  ```
  $ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  $ php -r "if (hash_file('sha384', 'composer-setup.php') === 'c31c1e292ad7be5f49291169c0ac8f683499edddcfd4e42232982d0fd193004208a58ff6f353fde0012d35fdd72bc394') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  $ php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  $ php -r "unlink('composer-setup.php');"
  ```

## composer install packages

  ```
  $ composer install 
  ```

### error

  1. "In PackageManifest.php line 131: Undefined index: name" 
      
      - rollback composer version 
      
        ```
        $ composer self-update --rollback
        ```

      - update packe version

        ```
        $ composer update
        ```

## run project 

```
$ php artisan serve
```

### error

  1. resopnse 500

      - change debug mode

        config/app.php set 'debug' => env('APP_DEBUG', True),

  2. "No application encryption key has been specified."

      ```
      $ 
      $ php artisan key:generate
      ``` 
  3. "Unsupported SSL request - Php artisan serve" 

      [參考stackoverflow #60407010](https://stackoverflow.com/questions/60407010/unsupported-ssl-request-php-artisan-serve)

      - Add below code in your app/Providers/AppServiceProvider.php file

        ```
        public function boot()
        {
          if (!$this->app->isLocal()) {
                $this->app['request']->server->set('HTTPS', true);
          }
        }
        ```

# Note for npm user

## npm vs laravel

- package.lock = composer.lock
- node_modules = vendeor