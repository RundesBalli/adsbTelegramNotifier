FROM php
LABEL maintainer "Die Peter Pan <diepeterpan@gmail.com>"

RUN apt-get update && apt-get install -y curl cron bash nano

COPY . /usr/src/myapp
WORKDIR /usr/src/myapp

COPY cronfile /etc/cron.d/cronfile
RUN chmod 0644 /etc/cron.d/cronfile
RUN crontab /etc/cron.d/cronfile

ENTRYPOINT ["/usr/sbin/cron", "-f"]
