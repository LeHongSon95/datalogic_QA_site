# ローカル環境用Makefile

.PHONY: build
build:
	docker compose build

.PHONY: up
up:
	docker compose up -d

.PHONY: down
down:
	docker compose down

# image及びvolumeも含めて全て削除
.PHONY: down-all
down-all:
	docker compose down --rmi all --volumes --remove-orphans

.PHONY: stop
stop:
	docker compose stop

.PHONY: start
start:
	docker compose start

.PHONY: web
web:
	docker compose exec web sh

.PHONY: app
app:
	docker compose exec app sh

.PHONY: app-root
app-root:
	docker compose exec -u=root app sh

.PHONY: db
db:
	docker compose exec db bash

.PHONY: composer-install
composer-install:
	docker compose exec app composer install

.PHONY: npm-install
npm-install:
	docker compose exec app npm install

.PHONY: npm-run
npm-run:
	docker compose exec app npm run dev

.PHONY: migrate-up
migrate-up:
	docker compose exec app php artisan migrate
