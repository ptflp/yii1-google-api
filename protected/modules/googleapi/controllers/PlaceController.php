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

    $cache = $this->container
                  ->get('PlaceSearchCache')
                  ->createListKey($cityId,$keyword)
                  ->requestList()
                  ->requestData();

    $dataCache = $cache->getData();

    $this->debug($dataCache);

    $placesApi = $this->container
                      ->get('PlaceSearch')
                      ->requestData($cityId,$keyword);

    $placesRaw = $placesApi->getPlacesRaw();
    $placesArray = PlaceSearch::preparePlacesRaw($placesRaw);

    $cache->setData($placesArray)
          ->saveData();

    foreach ($placesArray as $item) {
      $data[] = [
        "name" => $item['name'],
        "longitude" => $item['longitude'],
        "latitude" => $item['latitude'],
        "address" => $item['address']
      ];
    }

    $this->debug($data);
  }

  public function createCacheKey(int $cityId, string $keyword) : string
  {
    if (mb_strlen($keyword)<10) {
      $keyword = base64_encode($keyword);
    } else {
      $keyword = md5($keyword);
    }

    $key = 'c:'.$cityId.':q:'.$keyword;

    return $key;
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