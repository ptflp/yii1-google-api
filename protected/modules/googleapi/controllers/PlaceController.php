<?php

class PlaceController extends Controller
{
	public function actionSearch()
	{
		if(isset($_GET['city'])&&isset($_GET['keyword'])) {
			$city = mb_strtolower($_GET['city']);
			$place = mb_strtolower($_GET['keyword']);

			$place = trim($place);

			$placeSearch = $this->container->get('PlaceSearch');

			$places = $placeSearch->getData($city,$place)->getResults();

			$this->renderJSON($places);
		}
	}
}