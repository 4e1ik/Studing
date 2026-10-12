init: docker-down-clear docker-pull docker-build docker-up

up: docker-up
down: docker-down
restart: down up
app: container-app
logs: docker-logs
ps: docker-ps

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

docker-down-clear:
	docker compose down -v --remove-orphans

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

container-app:
	docker exec -it app_studing bash

docker-logs:
	docker compose logs -t

docker-ps:
	docker compose ps

# Полный рефреш БД + сиды + Passport + очистка кэша
# Использование: make db-refresh
db-refresh:
	@APP_ENV=$$(grep '^APP_ENV=' laravel/.env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	if [ "$$APP_ENV" = "production" ]; then \
		echo "Refuse to run db-refresh on production (APP_ENV=production)"; exit 1; \
	fi
	docker exec app_studing php artisan migrate:fresh --force && \
	docker exec app_studing php artisan db:seed --force
	@echo "--- Personal Access Client ---"
	@printf '\n\n' | docker exec -i app_studing php artisan passport:client --personal
	@echo "--- Password Grant Client ---"
	@printf '\n\n' | docker exec -i app_studing php artisan passport:client --password

cache-clear:
	@echo "--- Очистка и кэш конфига ---"
	docker exec app_studing php artisan config:clear
	docker exec app_studing php artisan cache:clear
	docker exec app_studing php artisan config:cache