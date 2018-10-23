<?php

class PlaceController extends Controller
{
	public function actionIndex()
	{
		if(isset($_GET['city'])&&isset($_GET['place'])) {
			$city = $_GET['city'];
			$place = $_GET['place'];
			$address = $place;
			$jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
			$key = json_decode($jsonKey);
			$key = $key->key;
			$container = new DI\Container();
			$gapi = $container->get('GooglePlacesApi');

			$cityObj = $gapi
						->setApiKey($key)
						->requestCitiesByName($city)
						->findOne()
						->requestDetails('geometry,address_components')
						->getResults();
			$placesDetails = $gapi
							->requestPlacesByCity($city,$place)
							->requestDetails('geometry,address_components,types')
							->getResults();
			$addressDetails = $gapi
								->requestAdressByCity($city,$address)
								->requestDetails('geometry,address_components')
								->getResults();
			$params = [
				'addressDetails'=>$addressDetails,
				'placesDetails'=>$placesDetails,
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