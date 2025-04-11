FROM alpine
RUN apk add apache2 php84 php84-curl php84-apache2

RUN rm /var/www/localhost/htdocs/index.html

COPY . /var/www/localhost/htdocs/

RUN rm /etc/apache2/httpd.conf
COPY ./httpd.conf /etc/apache2/httpd.conf

EXPOSE 80
CMD httpd -DNO_DETACH
