<?php

class OauthController extends Controller
{
	public $defaultAction='authorize';

	public function actionAuthorize()
	{
		if(Yii::app()->user->isGuest){

				$this->redirect($oauthUrl);
		  }else{
				$this->redirect(Yii::app()->homeUrl);
		  }
	}
}