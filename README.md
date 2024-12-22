Setup:

./vendor/bin/sail up -d
./vendor/bin/sail bash
composer install
php artisan migrate

php artisan db:seed can be run to get last 10 days of data

multiplier settings set in .env file ELECTRICITY_PRICE_MULTIPLIER

api bearer token can be aquired with api/login

---------------------------------------------------------------------
Summary:

frontend: http://0.0.0.0/
swagger: http://0.0.0.0/api/documentation

Created prices model,
Sevice NORDPoolService to fetch the data,
Artisan command app:get-prices and added it to scheduler,
Enviroment variable for the multiplier,
Added laravel/breeze for the authentication,
Added laravel/sanctum for the api and created endpoints for current/next hour price,
Added chart.js for the chart and flatpickr for datepicker,
Added swagger for the api documentation.