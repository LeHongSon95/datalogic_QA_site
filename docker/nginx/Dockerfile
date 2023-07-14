FROM nginx:1.23-alpine

RUN apk --no-cache --update add openssl

RUN openssl genrsa 2048 > server.key \
  && openssl req -new -key server.key -subj "/C=JP/ST=Osaka/L=Osaka/O=Sample Corp/OU=develop/CN=localhost" > server.csr \
  && openssl x509 -req -days 3650 -signkey server.key -in server.csr -out server.crt \
  && cp server.crt /etc/nginx/server.crt \
  && cp server.key /etc/nginx/server.key \
  && chmod 400 /etc/nginx/server.key
