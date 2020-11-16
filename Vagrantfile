# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "gbailey/amzn2"
    config.vm.box_version = "20201112.0.0"

    config.vm.hostname = "gamdonglove"
    config.vm.network "private_network", ip: "192.168.40.12"
    config.vm.network "forwarded_port", guest: 8888, host: 8888, id: "drush rs"
    config.vm.network "forwarded_port", guest: 3000, host: 3000, id: "yarn watch"
    config.vm.synced_folder ".", "/vagrant", type: 'nfs'

    config.vm.provider "virtualbox" do |vb|
      vb.name = "gamdonglove"
      vb.cpus = 2
      vb.memory = "2048"
      # https://askubuntu.com/a/1273081
      vb.customize [ "modifyvm", :id, "--uartmode1", "file", File::NULL ]
    end

    config.vm.provision "shell", inline: <<-SHELL
    # set timezone to Asia/Seoul
    rm -f /etc/localtime && ln -s /usr/share/zoneinfo/Asia/Seoul /etc/localtime
    # add swap
    chmod +x /vagrant/config/provision/add-swap.sh && /vagrant/config/provision/add-swap.sh

    # install apache2, mariadb-10.2
    amazon-linux-extras enable lamp-mariadb10.2-php7.2
    yum -y install git deltarpm mariadb-server httpd patch

    # mysql_secure_installation
    mysql --user=root -e "DELETE FROM mysql.user WHERE User='';\
      DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');\
      DROP DATABASE IF EXISTS test; DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';\
      CREATE DATABASE IF NOT EXISTS gamdonglove CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\
      USE gamdonglove; SOURCE /vagrant/dump/dev.sql;
      GRANT ALL PRIVILEGES ON `gamdonglove`.* to vagrant@'localhost' IDENTIFIED BY 'vagrant'; FLUSH PRIVILEGES;"

    amazon-linux-extras disable docker
    yum -y install php-cli php-mysqlnd php-fpm php-gd php-opcache php-xml php-zip php-mbstring

    cp /vagrant/config/provision/localhost.vhost.conf /etc/httpd/conf.d/
    systemctl enable httpd mariadb php-fpm && systemctl start httpd mariadb php-fpm
  SHELL

  config.vm.provision "shell", privileged: false, inline: <<-SHELL
    # install composer
    curl -sS https://getcomposer.org/installer | sudo php -- --version=1.10.16 --install-dir=/usr/local/bin --filename=composer
    echo 'export PATH=/home/vagrant/.config/composer/vendor/bin/:$PATH' >> ~/.bash_profile && source ~/.bash_profile
    composer global require hirak/prestissimo

    # install drush launcher
    wget -O drush.phar https://github.com/drush-ops/drush-launcher/releases/latest/download/drush.phar
    chmod +x drush.phar && sudo mv drush.phar /usr/local/bin/drush

    # install nodejs
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.34.0/install.sh | bash
    . ~/.nvm/nvm.sh && nvm install --lts
    npm -g i yarn

    cd /vagrant && composer install
    cp -r /vagrant/config/provision/*.php /vagrant/app/sites/default/
  SHELL

  end
