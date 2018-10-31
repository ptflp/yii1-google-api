<?php

class PlaceController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			 array('allow',  // allow all users to perform 'index' and 'view' actions
				  'actions'=>array('search','findcity'),
				  'users'=>array('@'),
			 ),
			 array('deny',  // deny all users
				 'users'=>array('*'),
				 'deniedCallback' => function() { Yii::app()->controller->redirect(array ('/site/index')); }
			 ),
		);
    }

    public function actionSearch()
    {
        if (!isset($_GET['city_id']) || !isset($_GET['keyword'])) {
            $this->renderJSON([]);
            return;
        }
        if (!is_numeric($_GET['city_id']) || mb_strlen($_GET['keyword'])<1) {
            $this->renderJSON([]);
            return;
        }

        $matchPercent = 61.8;
        if (isset($_GET['match_percent'])) {
            if (is_numeric($_GET['match_percent'])) {
                $matchPercent = $_GET['match_percent'];
            }
        }

        $addressLimit = 8;
        if (isset($_GET['addresses_limit'])) {
            if (is_numeric($_GET['addresses_limit'])) {
                $addressLimit = $_GET['addresses_limit'];
            }
        }

        $placesLimit = 8;
        if (isset($_GET['places_limit'])) {
            if (is_numeric($_GET['places_limit'])) {
                $placesLimit = $_GET['places_limit'];
            }
        }

        $cityId = $_GET['city_id'];
        $keyword = $_GET['keyword'];

        $data = $this   ->container
                        ->get('DataWrapper')
                        ->setPlacesMatch($matchPercent)
                        ->setAddressesLimit($addressLimit)
                        ->setPlacesLimit($placesLimit)
                        ->requestData($cityId, $keyword)
                        ->getData();

        $this->renderJSON($data);
    }

    public function actionFindcity()
    {
        if (($_GET['city_name'])) {
            $cityName = mb_strtolower($_GET['city_name']);
            $cityName = trim($cityName);

            $cities = $this->container
                            ->get('Modules\GoogleApi\Models\PlaceSearch')
                            ->requestCitiesByName($cityName)
                            ->getCities();

            $this->renderJSON($cities);
        } else {
            $this->renderJSON([]);
        }
    }
}
