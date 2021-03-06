dup: memory
	sudo -s docker-compose up --abort-on-container-exit

ddown:
	sudo -s docker-compose down

dbuild: memory
	sudo -s docker-compose up --build --abort-on-container-exit
	#sudo -s docker-compose up --build --remove-orphans

memory:
	sudo sysctl -w vm.max_map_count=262144

view-clear:
	sudo -s docker-compose exec php-cli php artisan view:clear

cache-clear:
	sudo -s docker-compose exec php-cli php artisan cache:clear


# использование docker для команды
test-docker:
	sudo -s docker exec app_php-fpm_1 vendor/bin/phpunit --colors=always

# использование docker-compose
test:
	sudo -s docker-compose exec php-fpm vendor/bin/phpunit

perm:
	sudo -s chown ${USER}:${USER} bootstrap/cache -R
	sudo -s chown ${USER}:${USER} storage -R
	sudo -s chmod 777 storage -R
	if [ -d "node_modules" ]; then sudo -s chown ${USER}:${USER} node_modules -R; fi
	if [ -d "public/build" ]; then sudo -s chown ${USER}:${USER} public/build -R; fi

assets-install:
	sudo -s docker-compose exec node yarn install

assets-rebuild:
	sudo -s docker-compose exec node npm rebuild node-sass --force

assets-dev:
	sudo -s docker-compose exec node yarn run dev

assets-watch:
	sudo -s docker-compose exec node yarn run watch










queue:
	docker-compose exec php-cli php artisan queue:work

horizon:
	#docker-compose exec php-cli php artisan horizon
	docker-compose exec php-fpm php artisan horizon

horizon-pause:
	docker-compose exec php-cli php artisan horizon:pause

horizon-continue:
	docker-compose exec php-cli php artisan horizon:continue

horizon-terminate:
	docker-compose exec php-cli php artisan horizon:terminate


