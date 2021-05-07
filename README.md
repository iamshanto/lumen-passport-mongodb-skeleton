# Develop REST API with Lumen and PASSPORT authentication

# Installation

1. Clone this repo

```
git clone 
```

2. Install packages

```
$ composer install
```

3. Create and setup .env file

```
make a copy of .env.example
$ copy .env.example .env
$ php artisan key:generate
put database credentials in .env file
```

4. Migrate and insert records

```
$ php artisan migrate
```
5. Passport setup

```
$ php artisan passport:keys
$ php artisan passport:install
```


