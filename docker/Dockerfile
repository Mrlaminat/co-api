FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG env

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install --no-install-recommends -y \
    wget \
    curl \
    vim \
    git \
    zip \
    unzip \
    procps \
    telnet \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get autoremove && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/*

# Install Extensions
RUN docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql


# Add XDEBUG
RUN if [ ! "$env" = "production" ] ; \
        then yes | pecl install xdebug \
            && echo "xdebug.remote_enable=1\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
                    "xdebug.remote_handler=dbgp\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
                    "xdebug.profiler_enable=0\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
            && docker-php-ext-enable xdebug ; \
        else echo This env is production ; \
    fi

# Create XDebug comand to enable and disable
COPY docker/php/php-no-xdebug /usr/local/bin/
COPY docker/php/php-xdebug /usr/local/bin/

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Copy existing application directory contents
COPY ./ /var/www/html

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
