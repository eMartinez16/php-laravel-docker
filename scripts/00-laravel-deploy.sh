#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Running user seed"
php artisan db:seed --class=UserSeeder

echo "Running seeders"
php artisan db:seed 

echo "Importando clientes.."
php artisan importar:clientes

echo "Importando ensayos.."
php artisan importar:ensayos
