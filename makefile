.PHONY: help

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
.DEFAULT_GOAL := help

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

build:
	docker-compose up --build

access:
	docker exec -it dnd-$(inst) bash

node:
	docker run -u 1000:1000 -itv $(PWD):/app -w /app node:19.1.0-bullseye bash

composer:
	docker run -itv $(PWD):/app -w /app composer composer update
