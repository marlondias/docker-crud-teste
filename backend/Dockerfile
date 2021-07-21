FROM wyveo/nginx-php-fpm:php73

RUN rm -f /etc/nginx/conf.d/default.conf
COPY nginx_conf/ /etc/nginx/conf.d/

WORKDIR /usr/share/nginx
COPY . .

#RUN composer install
RUN cp .env.example .env
RUN chmod 775 -R storage/ bootstrap/
#RUN php artisan key:generate
#RUN php artisan migrate:fresh --seed
