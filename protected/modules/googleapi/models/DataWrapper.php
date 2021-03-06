<?php

namespace Modules\GoogleApi\Models;

use Modules\GoogleApi\Models\PlaceSearch;

/**
 * Google Places API data wrapper
 * returns places data && addresses data
 */

class DataWrapper
{
    protected $placeSearch;
    protected $placesCache;
    protected $addressesCache;
    protected $cityId;
    protected $keyword;
    protected $addressesLimit = 8;
    protected $placesLimit = 13;
    protected $placesMatch = 61.8;
    protected $placesData = [];
    protected $addressesData = [];
    protected $data = [];

    public function __construct(PlaceSearch $placeSearch, PlacesCache $placesCache, AddressesCache $addressesCache)
    {
        $this->placeSearch = $placeSearch;
        $this->placesCache = $placesCache;
        $this->addressesCache = $addressesCache;
    }

    public function requestData(int $cityId, string $keyword)
    {
        $this->setRequestParams($cityId, $keyword);

        if (!$this->checkCache()) {
            $this->placeSearch();
        }

        return $this;
    }

    protected function setRequestParams(int $cityId, string $keyword)
    {
        $this->cityId = $cityId;
        $keyword = mb_strtolower($keyword);
        $keyword = trim($keyword);
        $this->keyword = $keyword;
    }

    public function setAddressesLimit(int $addressesLimit)
    {
        $this->addressesLimit = $addressesLimit;

        return $this;
    }

    public function setPlacesLimit(int $placesLimit)
    {
        $this->placesLimit = $placesLimit;

        return $this;
    }

    public function setPlacesMatch(int $placesMatch)
    {
        $this->placesMatch = $placesMatch;

        return $this;
    }

    protected function prepareAddressesOutput()
    {
        $temp = [];
        $data = array_slice($this->addressesData, 0, $this->addressesLimit);
        foreach ($data as $item) {
            $temp[] = [
                "name" => $item['name'],
                "longitude" => floatval($item['longitude']),
                "latitude" => floatval($item['latitude']),
                "address" => $item['address']
            ];
        }
        $this->addressesData = $temp;
    }

    public function preparePlacesOutput()
    {
        $temp = [];
        $types = $this->placeSearch->getTypes();
        foreach ($this->placesData as $item) {
            $percent = $this->checkMatch($item['name']);
            if ($percent >= $this->placesMatch ) {
                // dump_r($key);
                $key = array_search($item['type'], array_column($types, 'en'));
                $type = '';
                if ($key !== false) {
                    if (mb_strlen($types[$key]['ru'])>0) {
                        $type =', ' . $types[$key]['ru'];
                    }
                }
                $temp[] = [
                    "name" => $item['name']. $type,
                    "longitude" => floatval($item['longitude']),
                    "latitude" => floatval($item['latitude']),
                    "address" => $item['address'],
                ];
            }
        }
        $temp = array_slice($temp, 0, $this->placesLimit);
        $this->placesData = $temp;
    }

    protected function checkMatch(string $itemName)
    {
        $itemName = mb_strtolower($itemName);
        similar_text($itemName, $this->keyword, $percent);
        if ($percent > $this->placesMatch) {
            return $percent;
        }
        $nameWords = explode(' ', $itemName);
        foreach ($nameWords as $name) {
            similar_text($name, $this->keyword, $percent);
            if ($percent > $this->placesMatch) {
                return $percent;
            }
        }
        $keyWords = explode(' ', $this->keyword);
        foreach ($keyWords as $keyword) {
            similar_text($itemName, $keyword, $percent);
            if ($percent > $this->placesMatch) {
                return $percent;
            }
        }

        return $percent;
    }

    protected function requestCache()
    {
        $cityId = $this->cityId;
        $keyword = $this->keyword;
        $placesCache = $this->placesCache;
        $addressesCache = $this->addressesCache;
        if ($placesCache->connect() || $addressesCache->connect()) {
            $placesCache->createListKey($cityId, $keyword)
                        ->requestList()
                        ->requestData();
            $addressesCache ->createListKey($cityId, $keyword)
                            ->requestList()
                            ->requestData();
        }
    }

    protected function checkCache()
    {
        $this->requestCache();
        $placesCache = $this->placesCache;
        $addressesCache = $this->addressesCache;

        $placesData = $placesCache->getData();
        $addressesData = $addressesCache->getData();
        if (count($placesData)>0 || count($addressesData)>0) {
            $this->addressesData = $addressesData;
            $this->placesData = $placesData;

            return true;
        }

        return false;
    }

    protected function placeSearch()
    {
        $cityId = $this->cityId;
        $keyword = $this->keyword;
        $this->placeSearch->requestData($cityId, $keyword);

        $addressRaw = $this->placeSearch->getAddressRaw();
        $placesRaw = $this->placeSearch->getPlacesRaw();
        $this->addressesData = PlaceSearch::prepareAddressRaw($addressRaw);
        $tempPlacesData = PlaceSearch::preparePlacesRaw($placesRaw);
        $this->placesData = $this->uniqueMultidimArray($tempPlacesData, "place_id");

        $this->cacheAddresses();
        $this->cachePlaces();
    }

    public function uniqueMultidimArray($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    protected function cacheAddresses()
    {
        if ($this->addressesCache->connect()) {
            $this->addressesCache->setData($this->addressesData)
                            ->saveData();
        }
    }

    protected function cachePlaces()
    {
        if ($this->placesCache->connect()) {
            $this->placesCache->setData($this->placesData)
                            ->saveData();
        }
    }

    public function concatData()
    {
        $this->prepareAddressesOutput();
        $this->preparePlacesOutput();

        foreach ($this->addressesData as $item) {
            $this->data[] = $item;
        }
        foreach ($this->placesData as $item) {
            $this->data[] = $item;
        }
    }

    public function getData()
    {
        $this->concatData();

        return $this->data;
    }
}
