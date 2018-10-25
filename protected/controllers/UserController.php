<?php

class UserController extends Controller
{
	public $defaultAction = 'settings';
	public function actionSettings()
	{
		$id = Yii::app()->user->id;

		$cityList = CityHelper::dropDownList();
		$model=$this->loadModel($id);
		if (empty(Yii::app()->user->getCity())) {
			$cityList[''] = 'Выберите город';
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			if($model->validate(array('city_id'=>$_POST['User']['city_id']))) {
				$model->city_id = $_POST['User']['city_id'];
				$model->save();
			}
		}

		$this->render('settings',array(
			'model'=>$model,
			'cityList'=>$cityList
		));
	}

	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}