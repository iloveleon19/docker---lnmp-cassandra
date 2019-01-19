FROM php:7.1-fpm

RUN apt-get update

RUN apt-get install -y wget iputils-ping libgmp-dev ruby zlib1g-dev

RUN docker-php-ext-configure zip --with-zlib-dir=/usr
RUN docker-php-ext-install mysqli zip  && docker-php-ext-enable mysqli zip

RUN wget http://downloads.datastax.com/cpp-driver/ubuntu/18.04/dependencies/libuv/v1.24.0/libuv1_1.24.0-1_amd64.deb \
    && wget http://downloads.datastax.com/cpp-driver/ubuntu/18.04/dependencies/libuv/v1.24.0/libuv1-dev_1.24.0-1_amd64.deb \
    && wget http://downloads.datastax.com/cpp-driver/ubuntu/18.04/cassandra/v2.11.0/cassandra-cpp-driver_2.11.0-1_amd64.deb \
    && wget http://downloads.datastax.com/cpp-driver/ubuntu/18.04/cassandra/v2.11.0/cassandra-cpp-driver-dev_2.11.0-1_amd64.deb

RUN dpkg -i libuv1_1.24.0-1_amd64.deb \
    && dpkg -i libuv1-dev_1.24.0-1_amd64.deb \
    && dpkg -i cassandra-cpp-driver_2.11.0-1_amd64.deb \
    && dpkg -i cassandra-cpp-driver-dev_2.11.0-1_amd64.deb

RUN pecl channel-update pecl.php.net && pecl install cassandra

# RUN bash -c 'echo -e "\n zlib.output_compression=On" >> /usr/local/etc/php/conf.d/php.ini'
# RUN bash -c 'echo -e "\n; DataStax PHP Driver\nextension=cassandra.so" >> /usr/local/etc/php/conf.d/php.ini'
RUN docker-php-ext-enable cassandra

RUN rm \
    libuv1_1.24.0-1_amd64.deb \
    libuv1-dev_1.24.0-1_amd64.deb \
    cassandra-cpp-driver_2.11.0-1_amd64.deb \
    cassandra-cpp-driver-dev_2.11.0-1_amd64.deb

RUN apt-get -y remove wget
