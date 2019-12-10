#!/bin/sh
export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/sbin:/usr/local/bin
echo "Current working directory: '"$(pwd)"'"
docker run --name drush-rs --rm -it -p 8888:8888 -v $(pwd):/app:delegated -v ~/.ssh:/root/.ssh drush drush rs 0.0.0.0:8888 -vvv
