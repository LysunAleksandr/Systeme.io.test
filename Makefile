.PHONY: dev
dev: ## Запуск приложения в development режиме на 8080-порту (сборка, миграции, запуск всех сервисов)
	docker-compose down
	docker-compose pull
	docker-compose up -d --build
	make app-update

.PHONY: app-update
app-update: ## Обновление запущеного контенера веб-приложения (пересборка, миграции)
	docker-compose exec php composer install --working-dir=./back
	docker-compose exec php back/bin/console doctrine:migrations:migrate -n
	docker-compose exec php back/bin/console doctrine:fixtures:load --append

.PHONY: stop
stop: ## Остановка всех контейнеров
	docker-compose down

.PHONY: php-cs-fixer
php-cs-fixer: ## Формирование кода
	docker-compose exec php back/vendor/bin/php-cs-fixer fix back/src/

.PHONY: login
login: ## Вход в контейнер веб-приложения (php)
	docker-compose exec php bash

.PHONY: db-diff
db-diff: ## Генерация файла миграции
	docker-compose exec php back/bin/console doctrine:migrations:diff

.PHONY: db-migrate
db-migrate: ## Миграции + фикстуры
	docker-compose exec php back/bin/console doctrine:migrations:migrate -n
	## docker-compose exec php back/bin/console doctrine:fixtures:load --append

.PHONY: db-clear
db-clear: ## Вызов deploy.hs
	docker-compose exec php sh back/deploy.sh

.PHONY: app-cache-clear
app-cache-clear: ## Очистка кеша
	docker-compose exec php back/bin/console cache:clear

db-list:
	docker-compose exec php back/bin/console doctrine:migrations:list

dump-restore:
	docker-compose restart
	docker-compose exec -T php back/bin/console doctrine:database:drop --force
	docker-compose exec -T php back/bin/console doctrine:database:create
	docker-compose exec -T database psql -U postgres -d devritm3 < back/dumps/dump.sql
	make db-migrate

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := prod