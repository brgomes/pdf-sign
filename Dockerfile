FROM php:7.4-fpm

ARG uid=1000

ARG user=user

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    freetds-dev \
    libpq-dev \
    libldap2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pdo_dblib --with-libdir=lib/x86_64-linux-gnu

RUN docker-php-ext-install pdo_pgsql pdo_dblib ldap mbstring exif pcntl bcmath gd pgsql

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

USER $user
