# Base repository

This skeleton is based on the Laravel Framework, which has thorough [documentation](https://laravel.com).

## Setup

To initialize the project run the `init.sh` script:
```bash
./init.sh
```
This script install dependecies via `composer`, creates a `.env` file, and sets up the app key.

## Starting the dev server
The application has a UI platform for testing.
After the initial configurations, the dev server can be started like:
```bash
php artisan serve
```

## Command
Use the command for the function, like this:
```bash
php artisan document_list 'invoice' 354 40000
```
