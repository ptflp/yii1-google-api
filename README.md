# Google Places API
## Разворачивание приложения
Добавить файл
``` g_api_key.json, client_secrets.json ``` в корень проекта (в той же папке где composer.json, docker-compose.yml)

g_api_key.json должен содержать API ключ, полученный в [Google API console](https://console.developers.google.com/apis/credentials):

```json
{
   "key": "FIzaSyFQbldR-7IWggcUOg_RVlckIwJXDGnKreY"
}
```

``` client_secrets.json ``` так же создается в [Google API console](https://console.developers.google.com/apis/credentials), в разделе Идентификаторы клиентов OAuth 2.0. Надо вписать редирект Url

Так же заменить редирект URL в конфиге /protected/confing/main.php
В разделе params, вписывается только домен с слэшем - http://places.ptflp.ru/

Для локального разворачивания изменить порт приложения с 80 на 8000 :

```yml
...
  php:
    image: yiisoftware/yii2-php:7.1-apache
    container_name: g-api-app
    volumes:
      - ~/.composer-docker/cache:/root/.composer/cache:delegated
      - ./:/app:delegated
    volumes_from:
      - tmp
    ports:
      - '8000:80'
    networks:
      - skynet
```

В ``` index.php ``` изменить YII_DEBUG в значение true

```bash
git clone https://github.com/ptflp/yii1-google-api.git
cd yii1-google-api
docker network create skynet # использую для внутреннюю свою сеть для управления контейнерами
docker-compose up
docker exec -it g-api-db mysql -proot
create database googleApi
exit
docker exec -it g-api-app bash
cd protected
./yiic migrate up
yes
```
Можно пользоваться

если происходит ошибка, то нужно пофиксить permission
```bash
chown -R www-data:www-data .
```

## Стэк
1. Server side:
    - docker-compose
    - [busybox](https://hub.docker.com/_/busybox/) (data container for unix sock)
    - [yiisoftware/yii2-php:7.1-apache](https://hub.docker.com/r/yiisoftware/yii2-php/tags/) Можно наверно было использовать 7.2 Не тестировал, взял из используемых.
    - [redis](https://hub.docker.com/_/redis/) Кэширование
    - [mysql](https://hub.docker.com/_/mysql/) используется latest

2. Backend:
    - [yiisoft/yii 1.1.16](https://github.com/yiisoft/yii/tree/1.1.16) не знаю почему именно эта версия
    - [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) хороший http клиент для POST GET запросов с параметрами. POST используется при авторизации
    - [php-di/php-di](https://packagist.org/packages/php-di/php-di) Dependency Injection Container
    - [predis/predis](https://packagist.org/packages/predis/predis) php redis

3. Frontend:
    - [UI Kit](https://getuikit.com/) material UI
    - [Vue.js](https://vuejs.org/)
    - [axios](https://github.com/axios/axios) js http client
    - [lodash](https://lodash.com/)
    - [jquery](https://jquery.com/)

4. Tools:
    - phpcs (PSR fix)
    - [git flow](https://danielkummer.github.io/git-flow-cheatsheet/index.ru_RU.html) git branching system

## Описание

Основным объектом запросов является  DataWrapper:
    - Выполняет функцию кэширования
    - Фильтрация данных
    - Отправляет запросы Google Places API

### Структура класса DataWrapper:

```
.
└── DataWrapper
    ├── AddressesCache // Кэширование адресов
    │   └── PredisClient
    ├── PlacesCache // Кэширование заведений
    │   └── PredisClient
    └── PlaceSearch // Запрос к Google Places API
        ├── City
        └── GooglePlacesApi
            └── ClientAdaptor
                └── GuzzleHttp

```

### Dependency Injection Container

DI.php - преобразован в компоненту, доступен через:
```php
Yii::app()->DI->container
```

### Основные моменты

1. Кэширование:
    - Идет обращение к спискам по ключам
        - PlaceCache:
```php
'c:'.$cityId.':p:'.$keyword
```
        - Возвращает список place_id
```php
"p:".$item['place_id'];
```
        - AddressCache:
```php
'c:'.$cityId.':a:'.$keyword;
```
        - Возвращает список place_id
```php
$key ="a:".$item['id'];
```

2. PlaceSearch (Поиск в Google Place API):
    - Multiple types queries deprecated. Поэтому пришлось захардкодить и делать запрос по каждому типу
    - Также при коротком запросе "лена" нет результата. Идет подстановка руссифицированных типоов "гостиница лена", "кинотеатр лена". Но не во всех типах такое поведение, надо тестировать.
    - При поиске мест использовался Google Place Search API - nearby search, так как Place Types [Table 1](https://developers.google.com/places/web-service/supported_types) можно использовать только в Place Search, в Autocomplete используется [Types Table 3](https://developers.google.com/places/web-service/supported_types) .
    - Идет поиск по координатам города radius 17000, ограничение поиска до 50000 метров.
    - При поиске города используется Google Place Autocomplete, затем координаты города берутся из Google Place Details. Данные сохраняются в базу, при поиске мест запросов в API по городам не происходит.

## Основные файлы проекта

Основная часть кода в папке modules.

```
.
├── components
│   ├── Controller.php
│   ├── DI.php // Dependency injection container php-di
│   ├── PhpAuthManager.php
│   └── WebUser.php
├── config
│   ├── auth.php
│   ├── console.php
│   ├── database2.php
│   ├── database.php
│   ├── di-config.php
│   ├── main.php
│   └── php-di.php
├── controllers
│   ├── SiteController.php
│   └── UserController.php
├── extensions
│   └── materialwidgets // Кривой виджет надо допиливать
│       ├── NavMenu.php
│       └── views
│           └── NavMenuView.php
├── migrations
│   ├── m181016_105613_city.php
│   └── m181016_140919_tbl_user.php
├── models
│   ├── CityHelper.php
│   ├── City.php
│   └── User.php
└── modules
    ├── admin
    │   ├── AdminModule.php
    │   ├── controllers
    │   │   ├── CityController.php
    │   │   └── UserController.php
    │   └── views
    │       ├── city
    │       │   └── index.php
    │       ├── default
    │       │   └── index.php
    │       ├── layouts
    │       │   ├── column1.php
    │       │   ├── column2.php
    │       │   ├── main.php
    │       │   └── material.php
    │       └── user
    │           ├── admin.php
    │           ├── create.php
    │           ├── _form.php
    │           ├── index.php
    │           ├── _search.php
    │           ├── update.php
    │           ├── _view.php
    │           └── view.php
    └── googleapi
        ├── components
        │   ├── ClientAdaptorInterface.php
        │   ├── ClientAdaptor.php
        │   ├── GoogleOauthInterface.php
        │   ├── GoogleOauth.php
        │   ├── GooglePlacesApiInterface.php
        │   ├── GooglePlacesApi.php // Много лишних методов, нужен рефактор
        │   └── UserIdentity.php
        ├── controllers
        │   ├── OauthController.php
        │   └── PlaceController.php
        ├── GoogleapiModule.php
        └── models
            ├── AddressesCache.php
            ├── DataWrapper.php
            ├── PlacesCache.php
            ├── PlaceSearch.php
            └── UserAuthorize.php
```