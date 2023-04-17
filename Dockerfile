#FROM gitlab.vascomm.co.id:4567/frontend-docker-image/php-8-fpm-composer:latest
FROM gitlab.vascomm.co.id:4567/frontend-docker-image/php-8-1-unicoop-v2:latest

# Set working directory
WORKDIR /var/www/html

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 20M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M;" >> /usr/local/etc/php/conf.d/uploads.ini

COPY . /var/www/html

RUN composer update --no-scripts --ignore-platform-reqs && \ 
    composer dump-autoload && \
    php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider" && \
    php artisan optimize:clear && \
    php artisan optimize

RUN chmod +x /var/www/html/nginx-conf/script.sh

# Expose port 9000 and start php-fpm server
ENTRYPOINT ["sh", "/var/www/html/nginx-conf/script.sh"]
