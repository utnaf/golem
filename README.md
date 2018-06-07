# Golem

**Long story short**: I got a pretty damn good name for an app and I need something to build around it. *No clue*. So I decided to make yet another Docker bootstrap for PHP. Just to have a running environment with PHP, nginx and mysql in a few commands. I usually do this when I need to do some small stuff, tests, package testing, or whatever you have to do without a framework.

## Usage

Just create a project via composer

```bash
composer create-project --prefer-dist utnaf/golem another-app
```

Start the containers and install composer dependencies:

```bash
make up
```

Now you can visit [http://localhost:8080](http://localhost:8080) or, if you want to be fancy, adding an entry to your `/etc/hosts` file with:
```
127.0.0.1 app.local
```

and then visit [http://app.local:8080](http://app.local:8080)

## Helpers
There are some other helpers in the Makefile to help you interacting with your docker containers:

``` bash
make kill # kills the containers
make build # rebuilds the containers
make composer [command] # runs a composer command on the container
make sh # use the shell on the app container
``` 

### Disclaimer
I'm not an expert in Docker, but this is working good for me. In any case please feel free to contribute in improving this if you feel the need by [writing an issue](https://github.com/utnaf/golem/issues/new).
