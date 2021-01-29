#!/usr/bin/bash
echo Board autorun start
php /var/www/board/artisan horizon &
exec /usr/bin/elasticsearch/elasticsearch-6.2.4/bin/elasticsearch -d
echo Board autorun end
