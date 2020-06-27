# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "mozodev/xenial64-anyenv"
    config.vm.hostname = "gamdonglove"
    config.vm.network "private_network", ip: "192.168.40.12"
    config.vm.network "forwarded_port", guest: 8888, host: 8888, id: "drush rs"
    config.vm.network "forwarded_port", guest: 3000, host: 3000, id: "yarn watch"
    config.vm.synced_folder ".", "/vagrant", type: 'nfs', fsnotify: true, exclude: ['vendor', 'dump', 'app/core', 'web/core', 'node_modules', 'app/sites', 'web/sites']
  
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
      echo 'export PATH="/home/vagrant/opt/mysql/5.7.26/bin:$PATH"' >> ~/.bashrc
      echo 'export PATH="/home/vagrant/sandboxes/msb_5_7_26:$PATH"' >> ~/.bashrc && source ~/.bashrc
      [ ! -d "/vagrant/dump/mysql_5_7_26" ] && mkdir -p /vagrant/dump/mysql_5_7_26
      [ ! -d "/vagrant/dump/mysql_5_7_26/gamdonglove" ] && sudo cp -r ~/sandboxes/msb_5_7_26/data/* /vagrant/dump/mysql_5_7_26/
      cp /vagrant/config/dev/my.sandbox.cnf /vagrant/config/dev/sb_include ~/sandboxes/msb_5_7_26/
      ~/sandboxes/msb_5_7_26/start
      ~/sandboxes/msb_5_7_26/use -e 'DROP DATABASE IF EXISTS test; CREATE DATABASE IF NOT EXISTS gamdonglove;'
  
      echo "[php] composer install"
      cd /vagrant && composer install
      sudo chmod +w /vagrant/app/sites/default/
      yes | sudo cp -r /vagrant/config/dev/settings* /vagrant/app/sites/default/
      sudo chmod -w /vagrant/app/sites/default/
    SHELL

    # https://www.vagrantup.com/docs/vagrantfile/vagrant_settings.html#config-vagrant-plugins
    config.vagrant.plugins = ["vagrant-vbguest", "vagrant-fsnotify"]
    # https://github.com/adrienkohlbecker/vagrant-fsnotify
    config.trigger.after :up do |t|
      t.name = "[host] vagrant-fsnotify"
      t.run = { inline: "vagrant fsnotify" }
      t.name = "[guest] Started mysql"
      t.run_remote = { inline: "/home/vagrant/sandboxes/msb_5_7_26/start" }
    end
  
  end
  