#!/bin/bash

# Iniciar o supervisor em background
/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf &

# Executar migrações e seeders
php artisan migrate --force
php artisan db:seed --force

# Manter o container rodando
php-fpm 