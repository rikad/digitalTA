# Install

With Web server (apache/nginx)
-------------------------------
1. download file
2. run "composer install" in root folder
3. copy .env.example to .env and edit with your configuration (database)
4. run "php artisan key:generate" in root_folder
5. run "php artisan migrate:referesh --seed" in root_folder
6. chmod -R 777 root_folder/storage
7. set document directory in webserver to root_folder/public


With artisan
-------------------------------
1. download file
2. run "composer install" in root folder
3. copy .env.example to .env and edit with your configuration (database)
4. run "php artisan key:generate" in root_folder
5. run "php artisan migrate:referesh --seed" in root_folder
6. chmod -R 777 root_folder/storage
7. run "php artisan serve" root_folder

