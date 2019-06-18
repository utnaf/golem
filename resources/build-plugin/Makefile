BASE_DOCKER_COMPOSE = docker-compose

up:
	$(BASE_DOCKER_COMPOSE) up --force-recreate -d 

kill:
	$(BASE_DOCKER_COMPOSE) kill

build:
	$(BASE_DOCKER_COMPOSE) stop \
	&& $(BASE_DOCKER_COMPOSE) rm -f \
	&& $(BASE_DOCKER_COMPOSE) pull \
	&& $(BASE_DOCKER_COMPOSE) build --no-cache

composer:
	$(BASE_DOCKER_COMPOSE) exec -u $(shell id -u):$(shell id -g) -e HOME=/tmp/ {appname}_web composer $(filter-out $@,$(MAKECMDGOALS))

sh:
	$(BASE_DOCKER_COMPOSE) exec -u $(shell id -u):$(shell id -g) -e HOME=/tmp/ {appname}_web bash

rm:
	$(BASE_DOCKER_COMPOSE) rm -f

reset: kill rm up
