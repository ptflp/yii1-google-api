<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city_id']) && isset($_GET['keyword'])) {
			if(is_numeric($_GET['city_id']) && mb_strlen($_GET['keyword'])>1) {
				$matchPercent = 89;
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
									->requestData($cityId,$place)
									->getResults();

				$this->renderJSON($places);
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