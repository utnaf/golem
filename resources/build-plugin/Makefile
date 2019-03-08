BASE_DOCKER_COMPOSE = docker-compose

up:
	$(BASE_DOCKER_COMPOSE) up --force-recreate -d 
.PHONY: up

kill:
	$(BASE_DOCKER_COMPOSE) kill
.PHONY: kill

build:
	$(BASE_DOCKER_COMPOSE) stop \
	&& $(BASE_DOCKER_COMPOSE) rm -f \
	&& $(BASE_DOCKER_COMPOSE) pull \
	&& $(BASE_DOCKER_COMPOSE) build --no-cache
.PHONY: build

composer:
	$(BASE_DOCKER_COMPOSE) exec {appname}_web /usr/local/bin/composer $(filter-out $@,$(MAKECMDGOALS))
.PHONY: composer

sh:
	$(BASE_DOCKER_COMPOSE) exec {appname}_web bash
.PHONY: sh

rm:
	$(BASE_DOCKER_COMPOSE) rm -f
.PHONY: rm

reset: kill rm up
.PHONY: reset

%:
	@:
