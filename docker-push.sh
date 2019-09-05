#!/usr/bin/env bash

if [ ! -n "$1" ]; then
    echo "invalid arguments: ./docker-push.sh TAG"
    exit 2
fi

TAG=$1

docker build -t mylxsw/wizard .

docker tag mylxsw/wizard mylxsw/wizard:$TAG
docker tag mylxsw/wizard:$TAG mylxsw/wizard:latest
docker push mylxsw/wizard:$TAG
docker push mylxsw/wizard:latest


