<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(!isset($_GET['city_id']) || !isset($_GET['keyword'])) {
      $this->renderJSON([]);
      return;
    }
    if(!is_numeric($_GET['city_id']) || mb_strlen($_GET['keyword'])<1) {
      $this->renderJSON([]);
      return;
    }

    $matchPercent = 61.8;
    if(isset($_GET['match_percent'])) {
      if(is_numeric($_GET['match_percent'])){
        $matchPercent = $_GET['match_percent'];
      }
    }

    $cityId = $_GET['city_id'];
    $keyword = mb_strtolower($_GET['keyword']);
    $keyword = trim($keyword);

    $placesCache = $this->container
                  ->get('PlacesCache');
    $addressesCache = $this->container
                  ->get('AddressesCache');
    if($placesCache->connect()) {
      $placesCache->createListKey($cityId,$keyword)
            ->requestList()
            ->requestData();
      $addressesCache->createListKey($cityId,$keyword)
            ->requestList()
            ->requestData();
    }

    $dataCache = $placesCache->getData();
    $addressesData = $addressesCache->getData();
    if (count($dataCache)>0) {
      $data = [];
      foreach ($dataCache as $item) {
        $data[] = [
          "name" => $item['name']. ', ' . $item['type'],
          "longitude" => $item['longitude'],
          "latitude" => $item['latitude'],
          "address" => $item['address']
        ];
      }
      $this->debug($addressesData);
      $this->debug($data);
      return;
    }

    $placesApi = $this->container
                      ->get('PlaceSearch')
                      ->requestData($cityId,$keyword);

    $placesRaw = $placesApi->getPlacesRaw();
    $addressRaw = $placesApi->getAddressRaw();
    $addressArray = PlaceSearch::prepareAddressRaw($addressRaw);
    $this->debug($addressArray);
    $placesArray = PlaceSearch::preparePlacesRaw($placesRaw);


    if($placesCache->connect()) {
      $placesCache->setData($placesArray)
                  ->saveData();
      $addressesCache->setData($addressArray)
                     ->saveData();
    }

    $data = [];
    foreach ($placesArray as $item) {
      $data[] = [
        "name" => $item['name']. ', ' . $item['type'],
        "longitude" => $item['longitude'],
        "latitude" => $item['latitude'],
        "address" => $item['address']
      ];
    }

    $this->debug($placesArray);
    $this->debug($data);
  }

	public function actionFindcity()
	{
		if(($_GET['city_name'])) {
			$cityName = mb_strtolower($_GET['city_name']);
			$cityName = trim($cityName);

			$cities = $this->container
								->get('PlaceSearch')
								->requestCitiesByName($cityName)
								->getCities();

			$this->renderJSON($cities);
		} else {
			$this->renderJSON([]);
		}
  }
}