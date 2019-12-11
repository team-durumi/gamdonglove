#!/bin/sh
export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/sbin:/usr/local/bin
echo "Current working directory: '"$(pwd)"'"
docker-compose exec drupal drush rs 0.0.0.0:8888
