dup:
	sudo -s docker-compose up

ddown:
	sudo -s docker-compose down

dbuild:
	sudo -s docker-compose up --build

test:
	sudo -s docker exec app_php-cli_1 vendor/bin/phpunit --colors=always

perm:
	sudo -s chown ${USER}:${USER} bootstrap/cache -R
	sudo -s chown ${USER}:${USER} storage -R
	if [ -d "node_modules" ]; then sudo -s chown ${USER}:${USER} node_modules -R; fi
	if [ -d "public/build" ]; then sudo -s chown ${USER}:${USER} public/build -R; fi

assets-install:
	sudo -s docker exec app_node_1 yarn install

assets-rebuild:
	sudo -s docker exec app_node_1 npm rebuild node-sass --force

assets-dev:
	sudo -s docker exec app_node_1 yarn run dev

assets-watch:
	sudo -s docker exec app_node_1 yarn run watch










queue:
	docker-compose exec php-cli php artisan queue:work

horizon:
	docker-compose exec php-cli php artisan horizon

horizon-pause:
	docker-compose exec php-cli php artisan horizon:pause

horizon-continue:
	docker-compose exec php-cli php artisan horizon:continue

horizon-terminate:
	docker-compose exec php-cli php artisan horizon:terminate

memory:
	sudo sysctl -w vm.max_map_count=262144
