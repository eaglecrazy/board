#!/usr/bin/bash
echo Board autorun start
exec /usr/bin/elasticsearch/elasticsearch-6.2.4/bin/elasticsearch -d
echo Elasticsearch started
php artisan horizon &
echo Board autorun end
