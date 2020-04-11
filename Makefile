build:
	git clean -fxd
	docker-compose run --rm composer

run:
	docker-compose run --rm scraper