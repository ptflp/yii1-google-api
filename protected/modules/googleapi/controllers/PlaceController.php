<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
    $placeTypes = $this->testData();
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
          $itemName = mb_strtolower($item->name);
          $words = explode(' ', $place);
          $input = $place;
          if (count($words) == 2) {
            $key0 = array_search($words[0], array_column($placeTypes, 'ru'));
            $key1 = array_search($words[1], array_column($placeTypes, 'ru'));
            if (is_int($key0)) {
              $input = $words[1];
            }
            if (is_int($key1)) {
              $input = $words[0];
            }
          }
          similar_text($itemName, $input, $percent);
          if($percent > $matchPercent) {
            if (mb_strlen($place)<10) {
              $keyword = base64_encode($_GET['keyword']);
            } else {
              $keyword = md5($_GET['keyword']);
            }
            $keyList = 'c:'.$cityId.':q:'.$keyword;

            $rlist=$redis->lrange($keyList, 0, -1);
            $search = array_search($item->place_id,$rlist);
            if (!is_int($search)) {
              $redis->rpush($keyList, $item->place_id);
            }
          }
          $key = $item->place_id;
          $arrayKey = array_search($item->types[0], array_column($placeTypes, 'en'));
          $name = $item->name . ', ' . $placeTypes[$arrayKey]['ru'];
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
        $rlist=$redis->lrange($keyList, 0, -1);
        $this->debug($rlist);
        $data = [];
        foreach ($rlist as $item) {
          $data[] = $redis->hgetall($item);
        }
        $this->debug($data);
        // $this->debug($placesRaw);

				// $this->renderJSON($places);
			} else {
				$this->renderJSON([]);
			}
		} else {
      echo mb_strlen($_GET['keyword']);
      $tmp = base64_encode($_GET['keyword']);
      echo "$tmp dlina ".strlen($tmp);
			// $this->renderJSON([]);
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
  
  public function testData()
  {
    return $placeTypes = [
      [
        'en' => 'movie_theater',
        'ru' => 'кинотеатр'
      ],
      [
        'en' => 'electronics_store',
        'ru' => 'Магазин электроники'
      ],
      [
        'en' => 'lodging', // сюда входят мини гостиницы и прочие
        'ru' => 'гостиница'
      ],
      [
        'en' => 'cafe',
        'ru' => 'кафе'
      ],
      [
        'en' => 'bar',
        'ru' => 'бар'
      ],
      [
        'en' => 'liquor_store',
        'ru' => 'Магазин спиртных напитков'
      ],
      [
        'en' => 'supermarket',
        'ru' => 'супермаркет'
      ]
    ];
  }
}