<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city'])&&isset($_GET['keyword'])) {
			$city = $_GET['city'];
			$place = $_GET['keyword'];
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
							->nearbySearch($place,'store')
							->getResults();
			// $addressDetails = $gapi
			// 					->requestAdressByCity($city,$address)
			// 					->requestDetails('geometry,address_components')
			// 					->getResults();
			$params = [
				// 'addressDetails'=>$addressDetails,
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