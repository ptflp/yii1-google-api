#!/bin/bash
sed -i "s/- '80:80'/- '8000:80'/" ./docker-compose.yml
docker network create skynet
docker-compose up -d
echo 'Wait for db initialization'
sleep 30s
docker exec g-api-db mysql -proot -e "create database googleApi"
docker exec -it g-api-app composer install
docker exec -it g-api-app /app/protected/yiic migrate --interactive=0
docker exec g-api-app bash ./fix_perm.sh