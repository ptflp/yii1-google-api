<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
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
		$this->render('index', $params);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}