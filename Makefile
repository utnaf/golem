BASE_DOCKER_COMPOSE = docker-compose -f build/docker-compose.yml

up:
	$(BASE_DOCKER_COMPOSE) up --force-recreate -d
.PHONY: up

kill:
	$(BASE_DOCKER_COMPOSE) kill
.PHONY: kill

build:
	$(BASE_DOCKER_COMPOSE) stop \
	&& $(BASE_DOCKER_COMPOSE) rm \
	&& $(BASE_DOCKER_COMPOSE) pull \
	&& $(BASE_DOCKER_COMPOSE) build
.PHONY: build

composer:
	$(BASE_DOCKER_COMPOSE) exec app /usr/local/bin/composer $(filter-out $@,$(MAKECMDGOALS))
.PHONY: composer

sh:
	$(BASE_DOCKER_COMPOSE) exec app bash
.PHONY: sh

%:
	@:
