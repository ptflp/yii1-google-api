<?php

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
    }

    public function setPlacesLimit(int $placesLimit)
    {
        $this->placesLimit = $placesLimit;
    }

    protected function prepareAddressesOutput()
    {
        $temp = [];
        $data = array_slice($this->addressesData, 0, $this->addressesLimit);
        foreach ($data as $item) {
            $temp[] = [
                "name" => $item['name'],
                "logitude" => floatval($item['longitude']),
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
        $data = array_slice($this->placesData, 0, $this->placesLimit);
        foreach ($data as $item) {
            $key = array_search($item['type'], array_column($types, 'en'));
            $temp[] = [
                "name" => $item['name']. ', ' . $types[$key]['ru'],
                "logitude" => floatval($item['longitude']),
                "latitude" => floatval($item['latitude']),
                "address" => $item['address']
            ];
        }
        $this->placesData = $temp;
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
        $this->placesData = PlaceSearch::preparePlacesRaw($placesRaw);

        $this->cacheAddresses();
        $this->cachePlaces();
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
