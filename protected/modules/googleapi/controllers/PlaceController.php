<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city_id']) && isset($_GET['keyword'])) {
			if(is_numeric($_GET['city_id']) && mb_strlen($_GET['keyword'])>1) {
				$matchPercent = 61.8;
				if(isset($_GET['match_percent'])) {
					if(is_numeric($_GET['match_percent'])){
						$matchPercent = $_GET['match_percent'];
					}
				}
				$cityId = $_GET['city_id'];
				$place = mb_strtolower($_GET['keyword']);

				$place = trim($place);

				$places = $this->container
									->get('PlaceSearch')
									->setMatchPercent($matchPercent)
                  ->requestData($cityId,$place);

        $placesRaw = $places->getPlacesRaw();

        // $addressRaw = $places->getAddressRaw();

        // $this->debug($addressRaw);
        $redis = $this->cache;
        foreach ($placesRaw as $item) {
          $key = 'c:'.$cityId.':p:'.$item->place_id;
          $name = $item->name . ', ' . $item->types[0];
          $lat = $item->geometry->location->lat;
          $lng = $item->geometry->location->lng;
          $address = substr($item->vicinity, 0, strrpos($item->vicinity, ","));
          $redis->hmset($key, [
            "name" => $name,
            "longitude" => $lng,
            "latitude" => $lat,
            "address" => $address
          ]);
        }
        $data = [];
        foreach ($placesRaw as $item) {
          $key = 'c:'.$cityId.':p:'.$item->place_id;
          $data[] = $redis->hgetall($key);
        }
        $this->debug($data);
        // $this->debug($placesRaw);

				// $this->renderJSON($places);
			} else {
				$this->renderJSON([]);
			}
		} else {
			$this->renderJSON([]);
		}
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