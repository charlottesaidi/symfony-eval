Eval Symfony
========================

### Requirements

* PHP 8.0.8 or higher

### Installation

```cmd 
composer install
```  

Create a .env and configure your variables : database and keys.

#### Database

Create the database :
```cmd
symfony console doctrine:database:create
```

Run migrations :
```cmd
symfony console doctrine:migrations:migrate
```

Run fixtures :
```cmd
symfony console doctrine:fixtures:load
```

### Usage

There's no need to configure anything to run the application. If you have
installed Symfony binary, run this command:

```cmd
symfony serve
```

Then access the application in your browser at the given URL (`https://localhost:8000` by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or configure a web server like Nginx or
Apache to run the application.

Navigate to `http://localhost:8000`. The application will automatically reload if you change any of the source files.
