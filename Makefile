.PHONY: up down build bash composer migrate fresh seed install

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

bash:
	docker compose exec app sh

composer:
	docker compose exec app composer $(cmd)

fix:
	docker compose exec app composer cs-fix

stan:
	docker compose exec app composer phpstan

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

restart: down up

logs:
	docker compose logs -f

shell:
	docker compose exec app /bin/sh

install:
	docker compose run --rm --no-deps app composer install --no-interaction --prefer-dist

setup: build up install migrate