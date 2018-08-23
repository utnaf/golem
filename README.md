# Golem

**Long story short**: I got a pretty damn good name for an app and I need something to build around it. *No clue*. So I decided to make yet another Docker bootstrap for PHP. Just to have a running environment with PHP, nginx and mysql in a few commands. I usually do this when I need to do some small stuff, tests, package testing.
Since is a Composer plugin, it can be use on existing projects too as long as these files does not exist:
 * `./Makefile`
 * `./build/docker`

It will fail otherwise.

## Usage

Is a Composer plugin, so just require it in your project. 
```bash
composer require --dev utnaf/golem
composer install
```

Or just add an entry to your **composer.json**
```
...
"require-dev": {
    ...
    "utnaf/golem": "^1.1.1"
},
```
And run `composer install`

### How do I make it work?

Just run:
```bash
make up
```

Now you can visit [http://localhost:8080](http://localhost:8080) or, if you want to be fancy, add an entry to your `/etc/hosts` file:
```
127.0.0.1 app.local
```

and then visit [http://app.local:8080](http://app.local:8080)

#### Note for non-Linux user
If you are using any kind of docker-machine please replace `127.0.0.1` with the IP of your docker-machine.

## Connect to the DB
Your DB host will be named `<project dir>_database`, the database itself is `<project dir>_db`, username and password will be `root`.

Ex: if your project is in the directory `my-awesome-project` the db hostname will be `myawesomeproject_database` and the DB `myawesomeproject_db`. 

# Makefile
There is a Makefile that allows you to easily interact with the docker container.

## Install a composer package
Maybe you have the need to install a package using the container composer version, in this case you want to use the `composer` helper:
```bash
make composer install psr/log
```

## Helpers
There are some other helpers in the Makefile to help you interacting with your docker containers:

``` bash
make kill # kills the containers
make build # rebuilds the containers
make composer [command] # runs a composer command on the container
make sh # use the shell on the app container
``` 

### Disclaimer
I'm not an expert in Docker, but this is working good for me. In any case please feel free to contribute in improving this if you feel the need by [writing an issue](https://github.com/utnaf/golem/issues/new). Any help or comment is highly appreciated.
