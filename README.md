## Setup
* Download source
* Use the following commands for setting Homestead and vagrant
    * composer require laravel/homestead --dev
    * php vendor/bin/homestead make
    * vagrant up
* Use QUEUE_CONNECTION=redis in the env for queue
* Create a database and update the env file, so you can access the homestead database in VM
* Make migration with: php artisan migrate 

## Generate Users
php artisan command:generate_users

## Import Users
php artisan command:import_users
 
## Run the Worker
php artisan queue:work

## Run Tests
php artisan test
