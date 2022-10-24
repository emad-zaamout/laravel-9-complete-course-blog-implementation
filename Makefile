.PHONY: help

CONTAINER_PHP=php
CONTAINER_NODE=node
CONTAINER_DATABASE=database

VOLUME_DATABASE=laravel-blog_db-vol

help: ## show Help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

ps: ## show containers
	@docker compose ps

fresh: stop destroy build start ## Destroy and recreate all containers.

start: ## Start the containers.
	@docker compose up --force-recreate -d

stop: ## Stop the containers.
	@docker compose -f docker-compose.yml down

restart: stop start ## Restart all containers.

destroy: stop ## Destroy all containers and volumes.
	@echo "Deleting Image: ${CONTAINER_PHP} ... "
	@if [ "$(shell docker ps -aqf name=^${CONTAINER_PHP})" ]; then \
		docker rm -vf  ${CONTAINER_PHP}; \
	else \
		echo "  - No such container: ${CONTAINER_PHP} exists."; \
	fi

	@echo "Deleting Image: ${CONTAINER_NODE} ... "
	@if [ "$(shell docker ps -aqf name=^${CONTAINER_NODE})" ]; then \
		docker rm -vf  ${CONTAINER_NODE}; \
	else \
		echo "  - No such container: ${CONTAINER_NODE} exists."; \
	fi

	@echo "Deleting Image: ${CONTAINER_DATABASE} ... "
	@if [ "$(shell docker ps -aqf name=^${CONTAINER_DATABASE})" ]; then \
		docker rm -vf  ${CONTAINER_DATABASE}; \
	else \
		echo "  - No such container: ${CONTAINER_DATABASE} exists."; \
	fi

	@echo "Deleting Volume: ${VOLUME_DATABASE} ... "
	@if [ "$(shell docker volume ls --filter name=${VOLUME_DATABASE} --format {{.Name}})" ]; then \
		docker volume rm ${VOLUME_DATABASE}; \
	else \
		echo "  - No such volume ${VOLUME_DATABASE} exists."; \
	fi

build: ## Build all containers.
	docker build --no-cache -t $(CONTAINER_PHP) -f Dockerfile .

cache: ## Cache Laravel
	docker exec ${CONTAINER_PHP} php artisan cache
	docker exec ${CONTAINER_PHP} php artisan view:cache
	docker exec ${CONTAINER_PHP} php artisan config:cache
	docker exec ${CONTAINER_PHP} php artisan event:cache
	docker exec ${CONTAINER_PHP} php artisan route:cache

cache-clear: ## Clear cache
	docker exec ${CONTAINER_PHP} php artisan cache:clear
	docker exec ${CONTAINER_PHP} php artisan view:clear
	docker exec ${CONTAINER_PHP} php artisan config:clear
	docker exec ${CONTAINER_PHP} php artisan event:clear
	docker exec ${CONTAINER_PHP} php artisan route:clear

migrate: ## Run migration files.
	docker compose up -d

migrate-fresh: ## Clear database and run all migrations.
	docker compose up -d

test: ## Run phpunit tests.
	docker compose up -d

test-html: ## Run phpunit tests and generate HTML coverage report.
	docker compose up -d

ssh-php: ## SSH into your php container.
	docker exec -it ${CONTAINER_PHP} sh

npm-dev: ## Compile frontend assets
	docker exec ${CONTAINER_NODE} npm run dev

npm-prod: ## Compile frontend assets
	docker exec ${CONTAINER_NODE} npm run production

logs: ## Print all docker logs.
	docker compose logs -f

logs-php: ## Print all php container logs.
	docker logs ${CONTAINER_PHP}

log-node: ## Print all node container logs.
	docker logs ${CONTAINER_NODE}

log-database: ## Print all database container logs.
	docker logs ${CONTAINER_DATABASE}

