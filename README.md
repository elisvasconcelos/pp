# PP Elis

- [Installation](#installation)
- [Useful commands](#useful-commands)
    - [Project commands](#project-commands)
        - [Start project](#start-project)
        - [Turn off project](#turn-off-project)
    - [Dependencies commands](#dependencies-commands)
        - [Install Composer dependencies](#install-composer-dependencies)
        - [Install NPM dependencies](#install-npm-dependencies)
    - [Database Commands](#database-commands)
        - [Run Migrations](#run-migrations)
        - [Seed database](#seed-database)
        - [Clean database](#clean-database)
    - [Test Commands](#test-commands)
        - [Run tests](#run-tests)
    - [Code Style Commands](#code-style-commands)
        - [Run Laravel Pint Code Style to **DETECT** code style problems](#run-laravel-pint-code-style-to-detect-code-style-problems)
        - [Run Laravel Pint to **FIX** code style problems](#run-laravel-pint-to-fix-code-style-problems)


# Installation
1. Clone the project
```shell
git clone https://github.com/elisvasconcelos/pp.git
```
1. Use the command `./vendor/bin/sail up` to start the application.
2. Use the command `docker-compose exec app composer install` to install dependencies.
2. Use the command `docker-compose exec app php artisan migrate` to start run migrations.
3. Use the command `docker-compose exec app php artisan db:seed` to populate the base.
4. Access http://localhost

# Useful commands
## Project commands
### Start project
```shell
./vendor/bin/sail up -d
```
### Turn off project
```shell
docker-compose down
```

## Dependencies commands
### Install Composer dependencies
```shell
docker-compose exec app composer install
```

## Database Commands
### Run Migrations
```shell
docker-compose exec app php artisan migrate
```
### Seed database
```shell
docker-compose exec app php artisan db:seed
```
### Up Schedule Commands
```shell
docker-compose exec app php artisan schedule:work
```

## Test Commands
### Run tests
```shell
docker-compose exec app vendor/bin/phpunit
```
## Code Style Commands
We use Laravel Pint. For more info access their [documentation](https://laravel.com/docs/10.x/pint).
### Run Laravel Pint Code Style to **DETECT** code style problems
```shell
docker-compose exec app vendor/bin/pint --test
```
