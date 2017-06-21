FROM php:7.1.6

COPY . /app

WORKDIR /app

RUN apt-get -y update && \
    apt-get -y install git curl openssl zip unzip

#ENTRYPOINT ["/app/entrypoint.sh"]

EXPOSE 8080

CMD ["php", "src/index.php"]