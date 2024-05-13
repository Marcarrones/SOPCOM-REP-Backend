# Docker Quick Guide

## Build Docker Image
docker build -f ./Dockerfile -t sopcom-backend-v3 .

## Start Container
docker run -p 1036:80 -d sopcom-backend-v3

## See All Containers
docker ps -a

## Stop Docker Container
docker stop <container_id>

## Remove Container
docker rm <container_id>

##Update Container
docker cp ./src/ <contanier_id>:/var/www/html/
