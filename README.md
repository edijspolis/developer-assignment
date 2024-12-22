Setup:

cp .env.example .env

composer require laravel/sail --dev

php artisan sail:install

./vendor/bin/sail up -d

./vendor/bin/sail bash

composer install

npm install

php artisan migrate

php artisan key:generate

npm run build

To get last 10 days of data run - php artisan db:seed

multiplier settings are set in .env file ELECTRICITY_PRICE_MULTIPLIER

api bearer token can be aquired with api/login

---------------------------------------------------------------------

frontend: http://0.0.0.0/

swagger: http://0.0.0.0/api/documentation
