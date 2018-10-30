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
    if($placesCache->connect()) {
      $placesCache->createListKey($cityId,$keyword)
            ->requestList()
            ->requestData();
    }

    $dataCache = $placesCache->getData();
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
      $this->debug($data);
      return;
    }

    $placesApi = $this->container
                      ->get('PlaceSearch')
                      ->requestData($cityId,$keyword);

    $placesRaw = $placesApi->getPlacesRaw();
    $addressRaw = $placesApi->getAddressRaw();
    $addressArray = PlaceSearch::prepareAddressRaw($addressRaw);

    echo 'address';
    $this->debug($addressArray);
    $this->debug($addressRaw);
    $placesArray = PlaceSearch::preparePlacesRaw($placesRaw);


    if($placesCache->connect()) {
      $placesCache->setData($placesArray)
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