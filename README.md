[![Build Status](https://travis-ci.org/utnaf/golem.svg?branch=master)](https://travis-ci.org/utnaf/golem)
[![Packagist Pre Release](https://img.shields.io/packagist/vpre/utnaf/golem.svg)](https://packagist.org/packages/utnaf)

# Golem

**Long story short**: I got a pretty damn good name for an app and I need something to build around it. *No clue*. So I decided to make yet another Docker bootstrap for PHP. Just to have a running environment with PHP, apache and mysql in a few commands. I usually do this when I need to do some small stuff, playgrounds, package testing, that needs a fast boostrap.

Since is a Composer plugin, it can be used on existing projects as long as these files and directories does not exist:
 * `./Makefile`
 * `./docker-compose.yml`
 * `./build/docker`

It will fail otherwise.

## Usage

Is a Composer plugin, so just require it in your project. 
```bash
composer require --dev utnaf/golem
```

### What I have?

You have:
 - `php:7.3-apache` Docker image with a virtual host configured on [http://app.local](http://app.local)
 - latest Composer version 
 - `mariadb:10.3` Docker image
 - a `Makefile` to easily handle your docker images

### How do I make it work?

Just run:
```bash
$ make up
```

Now you can visit [http://localhost](http://localhost) or add an entry to your `/etc/hosts` file:
```
127.0.0.1 app.local
```

and then visit [http://app.local:8080](http://app.local:8080)

#### Note for docker-machine user
If you are using any kind of docker-machine please replace `127.0.0.1` with the IP of your docker-machine.

## Connect to the DB
Your DB host will be named `<project dir>_database`, the database itself is `<project dir>_db`, username and password can be found in the `docker-compose.yml`.

Ex: if your project is in the directory `my-awesome-project` the db hostname will be `myawesomeproject_database` and the DB `myawesomeproject_db`. 

# Makefile
There is a Makefile that allows you to easily interact with the docker container.

``` bash
$ make up
$ make kill
$ make build
$ make reset
$ make composer [command]
$ make sh
``` 

### Disclaimer
I'm not an expert in Docker, but this is working good for me. In any case please feel free to contribute in improving this if you feel the need by [writing an issue](https://github.com/utnaf/golem/issues/new). Any help or comment is highly appreciated.
