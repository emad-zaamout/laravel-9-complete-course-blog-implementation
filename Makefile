.PHONY: help

CONTAINER_PHP=php
CONTAINER_NODE=node
CONTAINER_DATABASE=database

VOLUME_DATABASE=laravel-blog_db-vol

help: ## Print help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

ps: ## Show containers.
	@docker compose ps

build: ## Build all containers
	@docker build --no-cache .

start: ## Start all containers
	@docker compose up --force-recreate -d

stop: ## Stop all containers
	@docker compose down