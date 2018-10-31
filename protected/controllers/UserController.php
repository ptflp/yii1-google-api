<?php

class UserController extends Controller
{
    public $defaultAction = 'settings';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',  // perform access control for CRUD operations
            'postOnly + delete',  // we only allow deletion via POST request
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
            array('allow',  // allow authenticated user to perform 'create' and 'update' actions
                    'actions'=>array('settings', 'save'),
                    'users'=>array('@'),
            ),
                array('deny',   // deny all users
                    'users'=>array('*'),
                    'deniedCallback' => function () {
                        Yii::app()->controller->redirect(array ('/site/index')); 
                    }
                ),
            );
    }


    public function actionSettings()
    {
        $id = Yii::app()->user->id;

        $cityList = CityHelper::dropDownList();
        $model=$this->loadModel($id);
        if (empty(Yii::app()->user->getCity())) {
            $cityList[''] = 'Выберите город';
        }

        $this->render('settings', array(
            'model'=>$model,
            'cityList'=>$cityList
        ));
    }

    public function actionSave()
    {
        if (isset($_POST['city_id'])) {
            $id = Yii::app()->user->id;
            $model=$this->loadModel($id);
            $cityId = $_POST['city_id'];
            if ($model->validate(array('city_id'=>$cityId))) {
                // Вариант 1
                $db = Yii::app()->db2;
                $sql = 'UPDATE tbl_user SET `city_id`=:city_id WHERE `id` = :userId';
                $command = $db->createCommand($sql);
                $command->bindParam(":city_id", $cityId, PDO::PARAM_INT); // экранируем запросы через PDO
                $command->bindParam(":userId", $id, PDO::PARAM_INT); // экранируем запросы через PDO
                $response = $command->execute(); //INSERT UPDATE DELETE returns (true, false or 1, 0)
                // Вариант 2
                // $model->city_id = $cityId;
                // $model->save(); // без параметра false идет валидация по правилам в модели
                $this->renderJSON($_POST);
            }
        }
    }

    public function loadModel($id)
    {
        $model=User::model()->findByPk($id);
        if ($model===null) :
            throw new CHttpException(404, 'The requested User does not exist.');
        endif;
        return $model;
    }
}
