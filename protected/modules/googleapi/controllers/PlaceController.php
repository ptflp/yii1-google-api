<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city_id']) && isset($_GET['keyword'])) {
			if(is_numeric($_GET['city_id']) && strlen($_GET['keyword'])>1) {
				$cityId = $_GET['city_id'];
				$place = mb_strtolower($_GET['keyword']);

				$place = trim($place);

				$places = $this->container
									->get('PlaceSearch')
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
}