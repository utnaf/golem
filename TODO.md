* Unique database does not work `WARNING: Service "database" is using volume "/var/lib/mysql" from the previous container. Host mapping "build_bdd_dbdata" has no effect. Remove the existing containers (with docker-compose rm database) to u...` Solution: rename the container, not the db or the volume
* Add a dummy class with autoloading
