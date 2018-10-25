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
			$cityId = $_POST['User']['city_id'];
			if($model->validate(array('city_id'=>$cityId))) {
				// Вариант 1
				$db = Yii::app()->db2;
				$sql = 'UPDATE tbl_user SET `city_id`=:city_id WHERE `id` = :userId';
				$command = $db->createCommand($sql);
				$command->bindParam(":city_id", $cityId, PDO::PARAM_INT);
				$command->bindParam(":userId", $id, PDO::PARAM_INT);
				$response = $command->execute(); //INSERT UPDATE DELETE
				// Вариант 2
				// $model->city_id = $cityId;
				// $model->save();
				$this->redirect('/user/settings');
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