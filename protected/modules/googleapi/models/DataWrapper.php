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
        $this->cityId = $cityId;
        $keyword = mb_strtolower($keyword);
        $keyword = trim($keyword);
        $this->keyword = $keyword;
        if ($this->checkCache()) {
        } else {
            $this->placeSearch();
        }
        foreach ($this->addressesData as $item) {
            $this->data[] = $item;
        }
        foreach ($this->placesData as $item) {
            $this->data[] = $item;
        }

        return $this;
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

    public function getData()
    {
        return $this->data;
    }
}
