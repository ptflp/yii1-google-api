<?php

interface GooglePlacesApiInterface
{
    public function requestCitiesByName(string $name);

    public function requestPlacesByName(string $name);

    public function requestPlacesByCity(string $city, string $place);

    public function requestAdressByCity(string $city, string $address);

    public function requestDetails(string $fields);

    public function getPlaceDetailsById(string $placeId, string $fields);

    public function findOne();

    public function getResults();

    public function setApiKey(string $key);

    public function setLanguage(string $lang);

    public function setRadius(int $radius);
}