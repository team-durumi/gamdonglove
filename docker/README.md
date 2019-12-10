# drush

- FROM drupal:fpm-alpine (PHP 7.3)
- FROM composer (composer 1.9)
- https://github.com/drush-ops/drush-launcher

```zsh
$ docker build -t drush ./
$ drush si standard --locale=ko --db-url=sqlite://../db/db.sqlite
```
