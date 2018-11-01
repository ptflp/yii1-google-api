#!/bin/bash
sed -i "s/- '80:80'/- '8000:80'/" ./docker-compose.yml
sed -i "s/'YII_DEBUG',false/'YII_DEBUG',true/" ./web/index.php
docker network create skynet
docker-compose up -d
echo 'Wait for db initialization'
sleep 30s
docker exec g-api-db mysql -proot -e "create database googleApi"
docker exec g-api-app composer install
docker exec g-api-app mkdir /app/protected/runtime
docker exec g-api-app bash ./fix_perm.sh
docker exec g-api-app /app/protected/yiic migrate --interactive=0