DOCKER_COMPOSE_CMD=docker-compose -f docker-compose.yml

help: ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: ## setup dev environment
	cp .env.example .env
	$(DOCKER_COMPOSE_CMD) build
	make start
	make composer-install
	make db-import

start: ## start containers
	$(DOCKER_COMPOSE_CMD) up -d --remove-orphans

stop: ## stop containers
	$(DOCKER_COMPOSE_CMD) stop

restart: ## stop and start containers
	make stop
	make start

composer-install: ## run composer install inside the container
	$(DOCKER_COMPOSE_CMD) exec php composer install

destroy: ## stop and remove containers, networks
	$(DOCKER_COMPOSE_CMD) down

console: ## run bash in the php container
	$(DOCKER_COMPOSE_CMD) exec php bash

db: ## run bash in the db container
	$(DOCKER_COMPOSE_CMD) exec mariadb bash

db-import: ## import dump file and anonymize it
#	sleep 5
	$(DOCKER_COMPOSE_CMD) exec -T mariadb mysql -u root -psecret feature_voting < fixtures/feature_voting.sql

logs: ## show php container logs
	$(DOCKER_COMPOSE_CMD) logs --follow --tail=30 php

.PHONY: help setup composer-install start composer-install stop destroy console db db-import logs
