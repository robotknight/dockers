#allen

FROM php:7-fpm

MAINTAINER allen " <luoye@live.cn> "

ENV TZ=Asia/Shanghai

#replace the srouce file? 
COPY sources.list /etc/apt/sources.list

RUN set -xe \
&& echo 'build dependencies >' \
&& buildDeps:=" \
	build-essential \
	php5-dev \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng12-dev \
" \
&& echo 'run dependencies >' \
&& runtimeDeps=" \
	libfreetype6 \
	libjpeg62-turbo \
	libmcrypt4 \
	libpng12-0 \
" \ 
&& echo 'intall php and compile the necessary package >' \
&& apt-get update \
&& apt-get install -y ${runtimeDeps} ${buildDeps} --no-install-recommends \
&& echo 'compile install php component' \
&& docker-php-ext-install iconv mcrypt mysqli pdo pdo_mysql zip \
&& docker-php-ext-configure gd \
	--with-freetype-dir=/usr/include/ \
	--with-jpeg-dir=/urs/include/ \
&& docker-php-ext-install gd \
&& echo 'purge >' \
&& apt-get purge -y --auto-remove \
	-o APT::AutoRemove::RecommendsImportant=false \
	-o APT::AutoRemove::SuggestsImportant=false \
	$buildDeps \
&& rm -rf /var/cache/apt/* \
&& rm -rf /var/lib/apt/lists/*

COPY ./php.conf /usr/local/etc/php/conf.d/php.conf
COPY ./site /usr/share/nginx/html 