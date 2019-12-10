#!/bin/sh
chmod +x drush.sh drush-rs.sh composer.sh
ln -s $(PWD)/drush.sh /usr/local/bin/drush
ln -s $(PWD)/drush-rs.sh /usr/local/bin/drush-rs
ln -s $(PWD)/drush-rs.sh /usr/local/bin/composer
