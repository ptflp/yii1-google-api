<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city'])&&isset($_GET['keyword'])) {
			$city = mb_strtolower($_GET['city']);
			$place = mb_strtolower($_GET['keyword']);
			$address = $place;
			$jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
			$key = json_decode($jsonKey);
			$key = $key->key;
			$container = new DI\Container();
			$gapi = $container->get('GooglePlacesApi')
									->setApiKey($key);

			$cityObj = $gapi
						->requestCitiesByName($city)
						->findOne()
						->requestDetails('geometry,address_components')
						->getResults();

			$placeTypes = [
				[
					'en' => 'movie_theater',
					'ru' => 'кинотеатр'
				],
				[
					'en' => 'lodging',
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


			$places = [];
			foreach ($placeTypes as $type) {
				$temp = $gapi
				->nearbySearch($type['ru'].' '.$place,$type['en'])
				->getResults();
				foreach ($temp as $item) {
					$itemName = mb_strtolower($item->name);
					if(strpos($itemName,$place) !== false){
						$name = $item->name . ', ' . $type['ru'];
						$lat = $item->geometry->location->lat;
						$lng = $item->geometry->location->lng;
						$address = $item->vicinity;
						$places[] = [
							"name" => $name,
							"longitude" => $lng,
							"latitude" => $lat,
							"address" => $address
						];
						// "name": "Лена, кинотеатр",
						// "longitude": 129.721214,
						// "latitude": 62.020283,
						// "address": "Ленина проспект, 45",
					}
				}
			}
			echo '<pre>';
			print_r($places);
			echo '</pre>';

			$addressDetails = $gapi
								->requestAdressByCity($city,$address)
								->getResults();
			$params = [
				'addressDetails'=>$addressDetails,
				'places'=>$places,
				'cityObj'=>$cityObj
			];

		} else {
			$params = [];
		}
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}
}