<?php

namespace Golem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

abstract class FilesystemTestCase extends TestCase
{
    const ROOT_DIR_NAME = 'golem-test-app';

    /** @var array */
    private $baseStructure;

    public function setUp()
    {
        $buildPluginDir = dirname(__DIR__) .
            DIRECTORY_SEPARATOR . 'resources' .
            DIRECTORY_SEPARATOR . 'build-plugin' .
            DIRECTORY_SEPARATOR;
        $dockerPhpDir = $buildPluginDir . 'build' .
            DIRECTORY_SEPARATOR . 'docker' .
            DIRECTORY_SEPARATOR . 'php' .
            DIRECTORY_SEPARATOR;

        $this->baseStructure = [
            'vendor' => [
                'utnaf' => [
                    'golem' => [
                        'resources' => [
                            'build-plugin' => [
                                'docker-compose.yml' => file_get_contents($buildPluginDir . 'docker-compose.yml'),
                                'Makefile' => file_get_contents($buildPluginDir . 'Makefile'),
                                'build' => [
                                    'docker' => [
                                        'php' => [
                                            'Dockerfile' => file_get_contents($dockerPhpDir . 'Dockerfile')
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array $structureOverride
     * @return vfsStreamDirectory
     */
    protected function getRootDir($structureOverride = [])
    {
        $structure = array_merge($this->baseStructure, $structureOverride);
        return vfsStream::setup(static::ROOT_DIR_NAME, null, $structure);
    }

    /** @return string */
    protected function getCompiledPhpDockerfile()
    {
        return <<<DOCKERFILE
FROM php:7.3-apache

# Enable mod rewrite
RUN a2enmod rewrite

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Copy default confg
RUN sed -ri -e 's!/var/www/html!\${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!\${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install Composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip libzip-dev
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) zip pdo pdo_mysql

WORKDIR /var/www/html

DOCKERFILE;
    }

    /** @return string */
    protected function getCompiledMakefile()
    {
        return <<<MAKEFILE
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
	$(BASE_DOCKER_COMPOSE) exec -u $(id -u):$(id -g) -e HOME=/tmp/ golemtestapp_web composer $(filter-out $@,$(MAKECMDGOALS))
.PHONY: composer

sh:
	$(BASE_DOCKER_COMPOSE) exec -u $(id -u):$(id -g) -e HOME=/tmp/ golemtestapp_web bash
.PHONY: sh

rm:
	$(BASE_DOCKER_COMPOSE) rm -f
.PHONY: rm

reset: kill rm up
.PHONY: reset

%:
	@:

MAKEFILE;
    }

    /** @return string */
    protected function getCompliledDockerCompose()
    {
        return <<<DOCKERCOMPOSE
version: "3"

services:
  golemtestapp_web:
    container_name: golemtestapp_web
    build:
      context: ./build/docker/php
    volumes:
      - ./:/var/www/html
    environment:
      - VIRTUAL_HOST=golemtestapp.local
      - APACHE_RUN_USER=www-data
      - APACHE_RUN_GROUP=www-data
    ports:
      - 80:80
    depends_on:
      - golemtestapp_database

  golemtestapp_database:
    container_name: golemtestapp_database
    image: mariadb:10.3
    volumes:
      - golemtestapp_dbdata:/var/lib/mysql
    environment:
      - MYSQL_DATABASE=golemtestapp_db
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_USER=golemtestapp
      - MYSQL_PASSWORD=golemtestapp123!
    ports:
      - 3306:3306

volumes:
  golemtestapp_dbdata:

DOCKERCOMPOSE;
    }
}
