# library

## Getting started

```
$ git clone https://github.com/astrasz/library.git
$ cd library/api
$ composer install
$ docker-compose up -d
```
After containers built: 
$ docker exec api php bin/console doctrine:migrations:migrate

If cache cannot be removed, exec first: 
$ docker exec api php bin/console cache:clear
