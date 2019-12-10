#!/bin/sh
rm -rf /usr/local/bin/drush /usr/local/bin/drush-rs /usr/local/bin/composer
chmod +x drush.sh drush-rs.sh composer.sh
ln -s $(PWD)/drush.sh /usr/local/bin/drush
ln -s $(PWD)/drush-rs.sh /usr/local/bin/drush-rs
ln -s $(PWD)/composer.sh /usr/local/bin/composer
