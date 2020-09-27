# Code Challenge

### Table of contents
- [Instruction](#instruction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Import](#import)
- [Unit Test](#unit-test)
- [Docker](#docker)
  - [Variables](#variables)
  - [Build](#build)
  - [Run](#run)

### Instruction
- Import customers from a 3rd party data provider (https://randomuser.me) and save to database.
- Display a list of customers from the database.
- Select and display details of a single customer from the database.
- Use Lumen or Syhamfony to write the following backend services in a single project.
- Store the customers in an SQL Type database and in `customers` table.
- Importer service should be constructed in a way that it can be used in any part of the Application or services or 
controllers such as API controllers, command, jobs and more.
- Create a console command.
- Update the customer if the email exists.
- Create two (2) RESTful API.
- Customer password should be hashed using the `md5` algorithm.
- The database should only store the information for this task only.
- The database layer should be [Doctrine](https://www.doctrine-project.org/projects/orm.html), [Laravel Doctrine ORM](http://www.laraveldoctrine.org/docs/1.3/orm)

### Requirements
- PHP >= 7.3
- Docker _(optional)_

### Installation
This project uses the latest or 8.x of [Lumen](https://lumen.laravel.com/docs/8.x).

1. Run the composer installation:
    ```sh 
    composer install
   ```

2. Create a copy `.env.example` and make your changes.

3. Provision the database:
    ```sh
    php artisan doctrine:schema:create
   ```

4. Serve the application by using [Laravel Homestead](http://laravel.com/docs/homestead), 
[Laravel Valet](http://laravel.com/docs/valet) or the built-in PHP development server:
    ```sh
    php -S localhost:8000 -t public
   ```

### Configuration
You can update the configuration on `config/customer.php` to add and edit driver for import on `importer_drivers` 
attribute.

Currently, we are only used [randomuser.me API](https://randomuser.me/documentation) as `default` driver. The config
file look like this:

```php
...
[
    'driver' => 'default', // The name of the driver
    'url' => 'https://randomuser.me/api/', // The url to request
    'version' => '1.3', // The default version
    'nationalities' => [ // An array values of nationalities
        'au'
    ],
    'fields' => [ // An array of included fields
        'name', // Where first and last name
        'email',
        'login', // Where username
        'gender',
        'location', // Where country and city
        'phone',
    ],
    'count' => 100, // How many results you wanted
],
...
```

### Import
You can easily import customers based on your default driver or `env('CUSTOMER_IMPORTER_DRIVER')` and run the command
below:

```sh
php artisan customer:import --count=[How many users to import, default: 100]
```

### Unit Test
You can skip the [Installation](#installation) part __BUT__ you need to run `php artisan doctrine:schema:create` on what
database or `env('DB_CONNECTION')` you are using the run:

```sh 
vendor/bin/phpunit
```

In case, you wanted to use `sqlite` for testing you can run:

```sh
DB_CONNECTION=sqlite vendor/bin/phpunit
```

### Docker
You can the application using [Docker](https://www.docker.com).

##### Variables
[Makefile](https://en.wikipedia.org/wiki/Makefile) is a good utility to make your compilation simpler. Here are the 
variables you can add on your make build like `make DB_CONNECTION=sqlite run`

| VARIABLES        | DESCRIPTION                                                    | TYPE   | DEFAULT                                        |
|------------------|----------------------------------------------------------------|--------|------------------------------------------------|
| `APP_DEBUG`      | Debug mode                                                     | bool   | false                                          |
| `APP_ENV`        | Environment                                                    | string | production                                     |
| `DB_HOST`        | Host of the database (default connect to your local database)  | string | host.docker.internal                           |
| `DB_DATABASE`    | The name of the database                                       | string | project_flexisourceit                          |
| `DB_USERNAME`    | The username to access the database                            | string | project                                        |
| `DB_PASSWORD`    | The password to access the database                            | string | project                                        |
| `APP_KEY`        | Application key                                                | string | null                                           |
| `PORT`           | Publish a container’s port(s) to the host                      | int    | 80                                             |
| `REPOSITORY_URL` | The url of the repository (default address of Docker registry) | string | 127.0.0.1:5000                                 |
| `REPOSITORY`     | The repository of the image                                    | string | ${REPOSITORY_URL}/flexisourceit/code-challenge |
| `VERSION`        | The version of the image                                       | string | latest                                         |
| `DOCKERFILE`     | The Dockerfile to build the image                              | string | Dockerfile                                     |

##### Build
[Build](https://docs.docker.com/engine/reference/commandline/build/) an image from Dockerfile.

```sh
make build
```

an alias of

```sh
docker build -t REPOSITORY_URL:latest -f Dockerfile --rm .
```

##### Run
[Run](https://docs.docker.com/engine/reference/commandline/run/) the application by:

```sh
make PORT=8000 run
```

Then, open your browser with [http://localhost:8000](http://localhost:8000).

* * *
###### Create and developed by [Jay Are Galinada](https://jayaregalinada.github.io)
