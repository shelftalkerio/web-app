FROM nginx:stable-alpine

ARG UID
ARG GID
 
ENV UID=${UID}
ENV GID=${GID}

ADD ./nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /var/www/html/backend

RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

RUN chown -R laravel:laravel /var/www/html/backend

COPY default.conf /etc/nginx/templates/default.conf.template
COPY docker-default.sh /

# Just in case the file mode was not properly set in Git
RUN chmod +x /docker-default.sh

# This will delegate to the original `docker-entrypoint.sh`
ENTRYPOINT ["/docker-default.sh"]

CMD ["nginx", "-g", "daemon off;"]