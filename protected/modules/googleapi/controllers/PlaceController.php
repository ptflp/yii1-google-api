<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city'])&&isset($_GET['keyword'])) {
			$city = mb_strtolower($_GET['city']);
			$place = mb_strtolower($_GET['keyword']);

			$jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
			$key = json_decode($jsonKey);
			$key = $key->key;
			$gapi = $this->container->get('GooglePlacesApi')
									->setApiKey($key);

			$cityObj = $gapi
						->requestCitiesByName($city)
						->findOne()
						->requestDetails('geometry,address_components')
						->getResults();
			// echo '<pre>';
			// print_r($cityObj);
			// echo '</pre>';
			/*
				надо бы найти и сопоставить google types
				и найти руссифицированные типы
				нашел в гугл мапс русские аналоги типов.
				Multiple types queries depricated прийдется по каждому типу выводить поочередно.
			*/
			$placeTypes = [
				[
					'en' => 'movie_theater',
					'ru' => 'кинотеатр'
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

			$places = [];

			$addressDetails = $gapi
								->requestAdressByCity($city,$place)
								->requestDetails('geometry')
								->getResults();
								// dump_r($addressDetails);
			array_slice($addressDetails,0,2);
			foreach ($addressDetails as $addressObj) {
				$name = $addressObj->structured_formatting->main_text;
				$lng = $addressObj->details->geometry->location->lng;
				$lat = $addressObj->details->geometry->location->lat;
				$places[] = [
					"name" => $name,
					"longitude" => $lng,
					"latitude" => $lat,
					"address" => $name
				];
			}

			foreach ($placeTypes as $type) {
				$addType = '';
				if(strpos($place,$type['ru']) == false) {
					$addType = $type['ru'];
				}
				$temp = $gapi
				->nearbySearch($addType.' '.$place,$type['en'])
				->getResults();
				foreach ($temp as $item) {
					$itemName = mb_strtolower($item->name);
					if(strpos($itemName,$place) !== false){
						$name = $item->name . ', ' . $addType;
						$lat = $item->geometry->location->lat;
						$lng = $item->geometry->location->lng;
						$address = substr($item->vicinity, 0, strrpos($item->vicinity, ","));
						// $address = $item->vicinity;
						$places[] = [
							"name" => $name,
							"longitude" => $lng,
							"latitude" => $lat,
							"address" => $address
						];
					}
				}
			}

			$this->renderJSON($places);

		}
	}
}