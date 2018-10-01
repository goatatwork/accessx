#!/usr/bin/env bash

# Set environment variables
export WEB_PORT=${WEB_PORT:-8088}
export DB_PORT=${DB_PORT:-3306}

# if the number of arguments passed to this script is gt (greater than) zero
# then pass those arguments onto the docker-compose command. If no
# arguments are passed, just run docker-compose ps
if [ $# -gt 0 ];then
    docker-compose "$@"
else
    docker-compose ps
fi
