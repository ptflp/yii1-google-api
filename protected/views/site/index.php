<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

if(isset($oauthLink)){
	echo $oauthLink;
}
if (isset($addressDetails)&&isset($placesDetails)&&isset($cityObj)) {
	echo '<pre>';
	print_r($addressDetails);
	echo '</pre>';

	echo '<pre>';
	print_r($placesDetails);
	echo '</pre>';

	echo '<pre>';
	print_r($cityObj);
	echo '</pre>';
}
if(Yii::app()->user->checkAccess('999')){
	echo "hello, I'm administrator";
}
if(isset($userInfo)){
	echo 'Email: '.$userInfo->email;
	echo '<br>';
	echo 'name: '.$userInfo->name;
	echo '<br>';
	echo 'link: '.$userInfo->link;
	echo '<br>';
	echo 'picture: <img src="'.$userInfo->picture.'"/>';
	echo '<br>';
	echo 'gender: '.$userInfo->gender;
	echo '<br>';
}
