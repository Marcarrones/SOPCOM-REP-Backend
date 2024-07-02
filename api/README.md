# Docker Quick Guide

## Build Docker Image
```powershell
docker build -f ./Dockerfile -t sopcom-backend-v3:backend .
```

## Remove dangling images
```powershell
docker rmi $(docker images -qa -f 'dangling=true')  
```
## Start Container
```powershell
docker run -p 1036:80 -d sopcom-backend-v3:backend
```

## See All Containers
```powershell
docker ps -a
```

## Stop Docker Container
```powershell
docker stop <container_id>
```

## Remove Container
```powershell
docker rm <container_id>
```

## Update Container
```powershell
docker cp ./src/ <contanier_id>:/var/www/html/
```