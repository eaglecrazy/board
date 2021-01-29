#!/usr/bin/bash
echo Board autorun start
/usr/bin/elasticsearch/elasticsearch-6.2.4/bin/elasticsearch -d
php artisan horizon &
echo Board autorun end
