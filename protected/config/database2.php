<?php

// This is the database connection configuration.
return array(
    // 'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
    'class' => 'system.db.CDbConnection', // Сайт yii framework раздел рецепты
    'connectionString' => 'mysql:host=g-api-db;dbname=googleApi',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
);
