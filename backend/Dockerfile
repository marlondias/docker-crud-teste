FROM wyveo/nginx-php-fpm:php73

RUN rm -f /etc/nginx/conf.d/default.conf
COPY ./nginx_conf/ /etc/nginx/conf.d/

WORKDIR /usr/share/nginx
COPY . .
RUN cp .env.example .env
RUN chmod 775 -R storage/ bootstrap/

RUN composer install
RUN php artisan key:generate
