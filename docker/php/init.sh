#!/usr/bin/env bash

cd /webroot/wizard && php /usr/bin/composer install

php /webroot/wizard/artisan migrate
php /webroot/wizard/artisan storage:link