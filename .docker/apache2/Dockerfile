FROM httpd:2.4-alpine

RUN apk update; \
    apk upgrade;

RUN apk add \
  bash \
  apache2 \
  curl \
  ca-certificates \
  openssl \
  git \
  tzdata \
  openntpd;

RUN sed -i "s/#LoadModule\ rewrite_module/LoadModule\ rewrite_module/" /usr/local/apache2/conf/httpd.conf;

# Copy our apache2 vhost file
COPY site.conf /usr/local/apache2/conf/

# Add our vhosts file to default config
RUN echo "Include /usr/local/apache2/conf/site.conf" >> /usr/local/apache2/conf/httpd.conf