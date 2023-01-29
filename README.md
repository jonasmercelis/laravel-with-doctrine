## Doctrine integration in Laravel.

The purpose of this project is only educative.

The goal is to make Doctrine ORM work with Laravel instead of the standard build-in ORM, Eloquent, while enabling as many features of Laravel as possible (authentication, authorization, Octane, Passport, etc ..) Some features of Laravel are tightly coupled with the standard ORM, so those will be skipped.

https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/index.html  
https://laravel.com

### Feature wishlist:


| What?                                       | Status   |
|---------------------------------------------|----------|
| Doctrine (Entity Manager)                   | ✅        |
| Doctrine Migrations (CLI)                   | ✅        |
| Authentication                              | ✅        |
| API Authentication                          | Todo     |
| Add instructions how to run this project.   | ✅        |
| Basic relations between entities            | Todo     |
| Authorization (Gates)                       | Todo     |
| Octane                                      | Todo     |

## How to run this application
This project is set up with Laravel Sail, so all external dependencies such as database, Redis, etc. are automatically setup with Docker.

1) Make sure the latest version of [Docker](https://www.docker.com) and Git are installed on your system.
2) Clone this repository to a folder local on your system.
3) Go to the root of the just cloned repository.
4) Then, copy the .env.example file to .env `cp .env.example .env`
5) Issue the following command:   
   `docker run --rm \ `  
   `-u "$(id -u):$(id -g)" \ `  
   `-v $(pwd):/var/www/html \ `  
   `-w /var/www/html \ `  
   `laravelsail/php82-composer:latest \ `  
   `composer install --ignore-platform-reqs `  
   This command will install all dependencies including Sail.
   See https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects for more information.
6) The container can be started by issuing `./vendor/bin/sail up` in the root folder of this project. Use the `-d` option to run in detached mode.
7) Run `./vendor/bin/sail php ./m migrations:migrate` to execute all DB migrations.
8) Finally, Generate the application key: `./vendor/bin/sail artisan key:generate`
9) The application will be served on http://localhost:80 (Make sure this port is not in use.)

### Change serving port

If it is desired to use another port instead of the default 80/tcp, you can change this with the .env file (found in the root of the project).
Add the following entry to your .env file:
`APP_PORT=<port number>`.
Finish by rebuilding the application container:
`./vendor/bin/sail build`
then `./vendor/bin/sail down && ./vendor/bin/sail up -d`
It is possible that restarting the containers without rebuilding the images is sufficient.


