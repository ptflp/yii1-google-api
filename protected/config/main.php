<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Google API Application',
    'language'=>'ru',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'admin',
        'googleapi',/*
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'test',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1','172.19.0.1'),
        ),*/
    ),

    // application components
    'components'=>array(
        'authManager' => array(
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => array('guest'),
        ),

        'DI' => array(
            'class' => 'DI',
        ),

        'user'=>array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin'=>true,
        ),

        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            'showScriptName' => false
        ),

        // database settings are configured in database.php
        'db'=>require(dirname(__FILE__).'/database.php'),
        // database settings are configured in database.php
        'db2'=>require(dirname(__FILE__).'/database2.php'),

        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>YII_DEBUG ? null : 'site/error',
        ),

        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'redirectUrl'=>'http://places.ptflp.ru/',
        'dicConfig'=>dirname(__FILE__).'/php-di.php',
        'adminEmail'=>'webmaster@example.com',
        'client_secrets'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../../client_secrets.json',
        'g_api_key'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../../g_api_key.json'
    ),
);
