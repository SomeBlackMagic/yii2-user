FROM php:7.2

RUN apt-get update && apt-get install -y --force-yes locales curl software-properties-common git zip unzip

# zip
RUN apt-get install -y zlib1g-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-enable zip

# pdo
RUN docker-php-ext-install pdo
RUN docker-php-ext-enable pdo

# pdo_mysql
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl https://getcomposer.org/installer > composer-setup.php && php composer-setup.php && mv composer.phar /usr/local/bin/composer && rm composer-setup.php

RUN composer -vvv global require hirak/prestissimo

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
