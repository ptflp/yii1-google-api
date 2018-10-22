<?php

class OauthController extends Controller
{
	public $defaultAction = 'authorize';

	public $GoogleOauth;

	public function actionAuthenticate()
	{
		if(!Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->homeUrl);
		}

		$this->setGoogleOauth();
		$oauthUrl = $this->GoogleOauth
				->createOauthLink()
				->getOauthLink();

		$this->redirect($oauthUrl);
	}

	public function actionAuthorize()
	{
		$get = $this->getActionParams();

		if(!Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->homeUrl);
		}

		if (!isset($get['code']) && !isset($get['scope'])) {
			$this->redirect(Yii::app()->homeUrl);
		}

		$this->setGoogleOauth();
		$userInfo = $this->GoogleOauth
											->requestOauthToken($get['code'])
											->requestUserInfo()
											->getUserInfo();

		$authorize = $this->container->get('UserAuthorize');
		$authorize->setEmail($userInfo->email)->login();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function setGoogleOauth()
	{
		$configPath =Yii::app()->params['client_secrets'];
		$this->GoogleOauth = $this->container
				->get('GoogleOauth')
				->loadFileConfig($configPath)
				->setRedirectUri('http://localhost:8000/googleapi/oauth/authorize')
				->addScope(GoogleOauth::USERINFO_EMAIL)
				->addScope(GoogleOauth::USERINFO_PROFILE);
	}
}