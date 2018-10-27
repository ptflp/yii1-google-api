<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city_id']) && isset($_GET['keyword'])) {
			if(is_numeric($_GET['city_id']) && strlen($_GET['keyword'])>1) {
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

			$places = $this->container
								->get('PlaceSearch')
								->setMatchPercent(1)
								->requestCitiesByName($cityName)
								->getResults();

			$this->debug($places);
		} else {
			$this->renderJSON([]);
		}
	}
}