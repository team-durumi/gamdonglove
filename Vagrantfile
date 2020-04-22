# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "mozodev/xenial64-anyenv"
    config.vm.hostname = "gamdonglove"
    config.vm.network "private_network", ip: "192.168.35.12"
    config.vm.network "forwarded_port", guest: 8888, host: 8888, id: "drush rs"
    config.vm.network "forwarded_port", guest: 3000, host: 3000, id: "yarn watch"
    config.vm.synced_folder ".", "/vagrant", type: 'nfs'
  
    config.vm.provider "virtualbox" do |vb|
      vb.name = "gamdonglove"
      vb.cpus = 2
      vb.memory = "2048"
      vb.linked_clone = true
    end
  
    config.vm.provision 'shell', keep_color: true, privileged: false, inline: <<-SHELL
      echo "[php] add dev config"
      cp /vagrant/config/dev/php-dev.ini ~/.anyenv/envs/phpenv/versions/7.3.16/etc/conf.d/
  
      echo "[mysql] copy default database to host, config to guest and start!"
      echo 'export PATH="/home/vagrant/opt/mysql/5.7.26/bin:$PATH"' >> ~/.bash_profile
      echo 'export PATH="/home/vagrant/sandboxes/msb_5_7_26:$PATH"' >> ~/.bash_profile && source ~/.bash_profile
      mkdir -p /vagrant/dump/mysql_5_7_26
      sudo cp -r ~/sandboxes/msb_5_7_26/data/* /vagrant/dump/mysql_5_7_26/
      cp /vagrant/config/my.sandbox.cnf /vagrant/config/sb_include ~/sandboxes/msb_5_7_26/
      ~/sandboxes/msb_5_7_26/start
      ~/sandboxes/msb_5_7_26/use -e 'DROP DATABASE IF EXISTS test; CREATE DATABASE IF NOT EXISTS gamdonglove;'
  
      echo "[php] composer install"
      cd /vagrant && composer install
      cp -r /vagrant/config/dev/settings* /vagrant/app/sites/default/
    SHELL

    config.trigger.after :up do |trigger|
      trigger.name = "Trying to start mysql 5.7.26..."
      trigger.run_remote = {inline: "start"}
    end
  
  end
  