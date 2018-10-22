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
		$container = new DI\Container();
		$oauth = $container->get('GoogleOauth');
		$configPath =Yii::app()->params['client_secrets'];
		$oauth->loadFileConfig($configPath);
		$oauth->setRedirectUri('http://localhost:8000/site/test')
			  ->addScope(GoogleOauth::USERINFO_EMAIL)
			  ->addScope(GoogleOauth::USERINFO_PROFILE)
			  ->createOauthLink();

		$oauthLink = '<a href="'.$oauth->getOauthLink().'">Google Auth URL</a>';

		if(isset($_GET['city'])&&isset($_GET['place'])) {
			$city = $_GET['city'];
			$place = $_GET['place'];
			$address = $place;
			$jsonKey = file_get_contents(Yii::app()->params['g_api_key']);
			$key = json_decode($jsonKey);
			$key = $key->g_api_key;
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
				'cityObj'=>$cityObj,
				'oauthLink'=>$oauthLink
			];

		} else {
			$params = ['oauthLink'=>$oauthLink];
		}
		$this->render('index', $params);
	}

	public function actionTest()
	{
		if (isset($_GET['code']) && isset($_GET['scope'])) {
			$container = new DI\Container();
			$oauth = $container->get('GoogleOauth');
			$configPath =Yii::app()->params['client_secrets'];
			$oauth->loadFileConfig($configPath);
			$oauth->setRedirectUri('http://localhost:8000/site/test')
				  ->addScope(GoogleOauth::USERINFO_EMAIL)
				  ->addScope(GoogleOauth::USERINFO_PROFILE)
				  ->createOauthLink();
			$oauth->requestOauthToken($_GET['code'])
				  ->requestUserInfo();
			$userInfo = $oauth->getUserInfo();

			$params = ['userInfo'=>$userInfo];
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
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		// dump_r($_POST);
		// die();
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
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