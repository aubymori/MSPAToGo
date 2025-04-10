FROM alpine
RUN apk add apache2 php84 php84-curl php84-apache2

RUN rm /var/www/localhost/index.html

COPY . /var/www/localhost/htdocs/

EXPOSE 80
CMD httpd -DNO_DETACH
